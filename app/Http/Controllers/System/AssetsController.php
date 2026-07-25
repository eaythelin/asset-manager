<?php

namespace App\Http\Controllers\System;

use App\Enums\AssetStatus;
use App\Enums\DisposalMethods;
use App\Enums\RequestStatus;
use App\Enums\WorkorderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetValidation;
use App\Models\AssetStatusLog;
use App\Models\Department;
use App\Models\AssetDisposalLog;
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
use App\Models\ActivityLog;
use App\Enums\ActivityAction;
use App\Enums\ActivityModule;
use App\Enums\RequestTypes;
use App\Models\Request as RequestModel;

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

        $assets = $query->paginate(5)->withQueryString();
        $columns = ["Asset Code", "Asset Name","Quantity","Serial Name","Department", "Custodian", "Category", "Status", "Actions"];

        return view('pages.assets.index-assets', ['desc' => $desc,
                                                              'assets' => $assets,
                                                              'columns' => $columns,
                                                              'disposalMethods' => DisposalMethods::cases()]);
    }

    public function getAsset($id){
        $asset = Asset::withTrashed()->with(['category', 'custodian', 'department', 'subCategory', 'supplier', 'requests'])->findOrFail($id);
        $disposalMethods = DisposalMethods::cases();
        $history = $asset->workorders()->with('completedBy')->where('workorders.status', WorkorderStatus::COMPLETED)->get();
        $columns = ["Workorder Code", "Control No.", "Type" ,"Start Date", "Date Finished", "Handled By" ,"Actions"];

        return view('pages.assets.show-asset', compact('asset','columns','history', 'disposalMethods'));
    }

    public function getCreateAsset(){
        //gets the latest asset id and add 1, if it doesnt exist default to AST-1
        $count = Asset::withTrashed()->count();
        $nextCode = 'AST-'.($count + 1);

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('name')->pluck('name', 'id');

        $suppliers = Supplier::orderBy('name')->pluck('name', 'id');

        return view('pages.assets.create-asset', compact('nextCode', 'categories', 'departments', 'employees', 'suppliers'));
    }

    public function getEditAsset($id){
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('name')->pluck('name', 'id');
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

        //store if duplicate
        $existing = Asset::where('name', $validated['asset_name'])
            ->where('serial_name', $validated['serial_name'])
            ->where('department_id', $validated['department'])
            ->first();

        if($existing && $request->confirm_merge == '1'){
            $existing->quantity += $validated['quantity'];
            $existing->save();

            ActivityLog::log(ActivityModule::ASSET, ActivityAction::UPDATED, "Merged asset: " . $validated['asset_name']);

            return redirect()->route('assets.index')->with('success', 'Asset merged successfully!');
        }


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

        ActivityLog::log(ActivityModule::ASSET, ActivityAction::CREATED, "Created asset: " . $validated['asset_name']);

        return redirect()->route('assets.index')->with('success', 'Asset successfully created!');
    }

    public function updateAsset(AssetValidation $request, $id){
        $asset = Asset::findOrFail($id);
        $validated = $request->validated();

        //make the is_depreciable true/false!
        $validated['is_depreciable'] = $request->has('is_depreciable');

        $existing = Asset::where('name', $request->asset_name)
            ->where('serial_name', $request->serial_name)
            ->where('department_id', $request->department)
            ->where('id', '!=', $id)
            ->first();

        if($existing && $request->confirm_merge == '1'){
            //check for existing request/workorders and cancel them all
            RequestModel::where('asset_id', $asset->id)
                ->whereNotIn('status', [RequestStatus::REJECTED->value, RequestStatus::CANCELLED->value])
                ->update(['status' => RequestStatus::CANCELLED->value]);

            Workorder::whereHas('request', function($q) use ($asset){
                $q->where('asset_id', $asset->id);
            })->whereNotIn('status', [WorkorderStatus::COMPLETED->value, WorkorderStatus::CANCELLED->value])
            ->update(['status' => WorkorderStatus::CANCELLED->value]);

            $existing->quantity += $asset->quantity;
            $existing->save();

            $asset->status = AssetStatus::DISPOSED->value;
            $asset->save();
            $asset->delete();

            ActivityLog::log(ActivityModule::ASSET, ActivityAction::UPDATED, "Asset '{$asset->name}' merged into asset ID {$existing->asset_code} (marked as disposed)");

            return redirect()->route('assets.index')->with('success', 'Asset merged successfully!');
        }

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

        ActivityLog::log(ActivityModule::ASSET, ActivityAction::UPDATED, "Updated asset: " . $validated['asset_name']);

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
                AssetDisposalLog::create([
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

                ActivityLog::log(ActivityModule::ASSET, ActivityAction::DISPOSED, "Disposed asset: " . $asset->name . ", Quantity Disposed: " . $validated['quantity']);
            });
        }catch(\Exception $e){
            dd($e->getMessage());
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

            ActivityLog::log(ActivityModule::ASSET, ActivityAction::CREATED, "Successfully imported assets");

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

    public function changeStatus(Request $request, $id){
        $validated = $request->validate([
          'status' => ['required', new Enum(AssetStatus::class)],
          'notes' => ['nullable', 'string', 'max:255']
        ]);

        $asset = Asset::findorFail($id);
        $oldStatus = $asset->status->value;

        $asset->update(['status'=> $validated['status']]);

        AssetStatusLog::create([
          'asset_id' => $asset->id,
          'changed_by' => auth()->user()->id,
          'from_status' => $oldStatus,
          'to_status' => $validated['status'],
          'notes' => $validated['notes'],
        ]);

        ActivityLog::log(ActivityModule::ASSET, ActivityAction::UPDATED, "Changed asset status of " . $asset->name . " to " . $asset->status->label());

        return redirect()->back()->with('success','Asset status updated successfully!');
    }

    public function getRequestPage($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $backRoute = 'assets.show';
        $backLabel = 'Asset';
        $requestTypes = RequestTypes::cases();

        return view('pages.workorders.show-workorder', compact('workorder', 'backRoute', 'backLabel', 'requestTypes'));
    }

    public function checkDuplicate(Request $request){
        $existing = Asset::where('name', $request->asset_name)
            ->where('serial_name', $request->serial_name)
            ->where('department_id', $request->department)
            ->first();

        return response()->json([
            'duplicate' => $existing ? true : false,
            'existing_asset' => $existing
        ]);
    }

    public function checkDuplicateOnEdit(Request $request, $id){
        $existing = Asset::where('name', $request->asset_name)
            ->where('serial_name', $request->serial_name)
            ->where('department_id', $request->department)
            ->where('id', '!=', $id)
            ->first();

        return response()->json([
            'duplicate' => $existing ? true : false,
            'existing_asset' => $existing
        ]);
    }
}
