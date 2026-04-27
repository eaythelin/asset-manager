<?php

namespace App\Http\Controllers\System;

use App\Enums\AssetStatus;
use App\Enums\DisposalMethods;
use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetValidation;
use App\Models\Department;
use App\Models\DisposalWorkorder;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Workorder;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetTemplateExport;
use App\Imports\AssetImport;
use Maatwebsite\Excel\Validators\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetsController extends Controller
{
    public function getAssets(Request $request){

        $role = auth()->user()->getRoleNames()->first();

        $query = Asset::with(['category', 'custodian', 'department', 'subCategory', 'supplier']);

        if(request('show_deleted')){
            $query->onlyTrashed();
        }

        if(request('search')){
            $search = $request->input('search');
            $query->search($search);
        }

        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $departmentid = auth()->user()->employee?->department_id;
            if($departmentid){
                $query->where('department_id', $departmentid);
            }
        }

        $desc = $role === "System Supervisor" ? "View and manage assets" : "View assets information";

        $assets = $query->paginate(5);
        $assets->each(fn($asset) => $asset->computed_status); //just checks if any asset is expired
        $columns = ["Asset Code", "Asset Name","Quantity","Serial Name","Department", "Custodian", "Category", "Status", "Actions"];

        return view('pages.assets.index-assets', ['desc' => $desc,
                                                              'assets' => $assets,
                                                              'columns' => $columns,
                                                              'disposalMethods' => DisposalMethods::cases()]);
    }

    public function getAsset($id){
        $asset = Asset::withTrashed()
            ->with(['category', 'custodian', 'department', 'subCategory', 'supplier', 'serviceWorkorders', 'disposalWorkorders', 'requisitionWorkorders'])
            ->findOrFail($id);
        $disposalMethods = DisposalMethods::cases();
        $history = $asset->serviceWorkorders
            ->concat($asset->disposalWorkorders)
            ->concat($asset->requisitionWorkorders)
            ->sortByDesc('created_at');

        $hasWorkorders = $asset->serviceWorkorders()->exists() || 
                 $asset->disposalWorkorders()->exists() || 
                 $asset->requisitionWorkorders()->exists();
        
        $columns = ["Workorder Code", "Type", "Status", "Start Date", "End Date", "Quantity", "Handled By"];

        return view('pages.assets.show-asset', compact('asset', 'disposalMethods','hasWorkorders','columns','history'));
    }

    public function getCreateAsset(){
        //gets the latest asset id and add 1, if it doesnt exist default to AST-1
        $count = Asset::withTrashed()->count();
        $nextCode = 'AST-'.($count + 1);
        
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->pluck('full_name', 'id');

        $suppliers = Supplier::orderBy('name')->pluck('name', 'id');

        return view('pages.assets.create-asset', compact('nextCode', 'categories', 'departments'
                                                                    , 'employees', 'suppliers'));
    }

    public function getEditAsset($id){
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->pluck('full_name', 'id');
        $suppliers = Supplier::orderBy('name')->pluck('name', 'id');

        $asset = Asset::with(['category', 'custodian', 'department', 'subCategory', 'supplier'])->findOrFail($id);

        return view('pages.assets.edit-asset', compact('asset', 'categories', 'departments', 'employees', 'suppliers'));
    }

    public function getSubcategories(Category $categoryID){
        return response()->json($categoryID->subCategories);
    }

    public function storeAsset(AssetValidation $request){
        $validated = $request->validated();

        //make the is_depreciable true/false!
        $validated['is_depreciable'] = $request->has('is_depreciable');

        $imagePath = null;
        //store the image in the public folder if uploaded!
        if($request->hasFile('image_path')){
            $imagePath = $request->file('image_path')->store('assets/images', 'public');
        }
        
        Asset::create([
            "name" => $validated['asset_name'],
            "serial_name" => $validated['serial_name'],
            "category_id" => $validated['category'],
            "quantity" => $validated["quantity"],
            "sub_category_id" => $validated['subcategory'] ?? null,
            "description" => $validated['description'],
            "image_path" => $imagePath,

            "department_id" => $validated['department'],
            "custodian_id" => $validated['custodian'] ?? null,

            "is_depreciable" => $validated['is_depreciable'],
            "acquisition_date" => $validated['acquisition_date'],
            "useful_life_in_years" => $validated['useful_life_in_years'],
            "end_of_life_date" => $validated['end_of_life_date'],
            "cost" => $validated['cost'] ?? 0,
            "salvage_value" => $validated['salvage_value'] ?? 0,
            
            "supplier_id" => $validated['supplier'] ?? null,
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset successfully created!');
    }

    public function updateAsset(AssetValidation $request, $id){
        $asset = Asset::findOrFail($id);
        $validated = $request->validated();

        //make the is_depreciable true/false!
        $validated['is_depreciable'] = $request->has('is_depreciable');

        //store the image in the public folder if uploaded!
        if($request->hasFile('image_path')){
            //delete old image file
            if($asset->image_path){
                Storage::disk('public')->delete($asset->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('assets/images', 'public');
        }

        $asset->update([
            "asset_code" => $validated['asset_code'],
            "name" => $validated['asset_name'],
            "serial_name" => $validated['serial_name'],
            "category_id" => $validated['category'],
            "quantity" => $validated["quantity"],
            "sub_category_id" => $validated['subcategory'] ?? null,
            "description" => $validated['description'],
            "image_path" => $validated['image_path'] ?? $asset->image_path,

            "department_id" => $validated['department'],
            "custodian_id" => $validated['custodian'] ?? null,

            "is_depreciable" => $validated['is_depreciable'],
            "acquisition_date" => $validated['acquisition_date'],
            "useful_life_in_years" => $validated['useful_life_in_years'],
            "end_of_life_date" => $validated['end_of_life_date'],
            "cost" => $validated['cost'] ?? 0,
            "salvage_value" => $validated['salvage_value'] ?? 0,
            
            "supplier_id" => $validated['supplier'] ?? null,
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset edited successfully!');
    }

    public function disposeAsset(Request $request,$id){
        $asset = Asset::findOrFail($id);
        $validated = $request->validate([
            //checks if the enum value exists
            "disposal_method" => ["required", new Enum(DisposalMethods::class)],
            "quantity"=> ["required", "integer","min:1"],
            "reason" => ["nullable", "string", "max:255"]
        ]);

        if($asset->status === AssetStatus::DISPOSED){
            return redirect()->route("assets.index")->with('error', 'This asset is already disposed!');
        }

        if($validated["quantity"] > $asset->quantity){
            return redirect()->route("assets.index")->with("error", "Disposal quantity exceeds available quantity!");
        }

        //this make is so the DB updates in one go and if anything fails then everything fails!!
        try{
            DB::transaction(function() use($validated, $asset){
                $count = Workorder::where('workorder_type', WorkorderType::DISPOSAL)->count();
                $nextCode = 'WO-DIS-'.($count + 1);

                $workorder = Workorder::create([
                    "workorder_code" => $nextCode,
                    "completed_by" => auth()->user()->id,
                    "start_date" => now(),
                    "end_date" => now(),
                    "priority_level" => PriorityLevel::HIGH,
                    "workorder_type" => WorkorderType::DISPOSAL,
                    "status" => WorkorderStatus::COMPLETED,
                    "is_direct" => true
                ]);

                DisposalWorkorder::create([
                    "workorder_id" => $workorder->id,
                    "asset_id" => $asset->id,
                    "disposal_method" => $validated['disposal_method'],
                    "disposal_date" => now(),
                    "quantity"=> $validated["quantity"],
                    "reason" => $validated['reason'] ?? null
                ]);

                $remaining = $asset->quantity - $validated['quantity'];
                if($remaining <= 0){
                    $asset->status = AssetStatus::DISPOSED;
                    $asset->save();
                    $asset->delete();
                }else{
                    $asset->quantity = $remaining;
                    $asset->save();
                }
            });
        }catch(\Exception $e){
            return redirect()->route("assets.index")->with('error', 'Something went wrong!');
        }

        return redirect()->route("assets.index")->with('success', 'Asset disposed successfully!');
    }

    public function downloadTemplate(){
        return Excel::download(new AssetTemplateExport, 'asset_import_template.xlsx');
    }

    public function importAssets(Request $request){
        $request->validate([
            'file_import' => ['required', 'file', 'mimes:xlsx,xls,csv']
        ]);

        try {
            Excel::import(new AssetImport, $request->file('file_import'));
            return redirect()->back()->with('success', 'Assets imported successfully!');
        } catch(ValidationException $e) {
            $failures = $e->failures();

            $errors = [];

            foreach($failures as $failure){
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()->with('import_errors', $errors);
        }
    }
}