<?php

namespace App\Http\Controllers\System;

use App\Enums\AssetStatus;
use App\Enums\DisposalMethods;
use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DisposalWorkorder;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Workorder;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\DB;

class AssetsController extends Controller
{
    public function getAssets(Request $request){

        $role = auth()->user()->getRoleNames()->first();

        $query = Asset::with(['category', 'custodian', 'department', 'subCategory', 'supplier']);

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
        $columns = ["Asset Code", "Asset Name", "Serial Name","Department", "Custodian", "Category", "Status", "Actions"];

        return view('pages.assets.index-assets', ['desc' => $desc,
                                                              'assets' => $assets,
                                                              'columns' => $columns,
                                                              'disposalMethods' => DisposalMethods::cases()]);
    }

    public function getAsset($id){
        $asset = Asset::with(['category', 'custodian', 'department', 'subCategory', 'supplier'])->findOrFail($id);
        $disposalMethods = DisposalMethods::cases();

        return view('pages.assets.show-asset', compact('asset', 'disposalMethods'));
    }

    public function getCreateAsset(){
        //gets the latest asset id and add 1, if it doesnt exist default to AST-1
        $latestAsset = Asset::withTrashed()->latest('id')->first();
        $nextCode = $latestAsset ? 'AST-' . ($latestAsset->id + 1): 'AST-1';
        
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

    public function storeAsset(Request $request){
        $validated = $request->validate([
            //general fields
            "asset_code" => ["required", "unique:assets"],
            "asset_name" => ["required", "string", "max:100"],
            "serial_name" => ["nullable", "string", "max:100"],
            "category" => ["required", "exists:categories,id"],
            "subcategory" => ["nullable", "exists:sub_categories,id"],
            "description" => ["nullable", "string", "max:255"],
            "image_path" => ["nullable", "image", "mimes:jpeg,png,jpg,gif", "max:2048"], //max 2MB

            //assignment fields
            "department" => ["required", "exists:departments,id"],
            "custodian" => ["nullable", "exists:employees,id"],

            //financial fields!!
            "is_depreciable" => ["nullable"],
            "cost" => ["required_if:is_depreciable,on", "nullable", "numeric", "min:0"],
            "salvage_value" => ["required_if:is_depreciable,on", "nullable", "numeric", "min:0"],
            "acquisition_date" => ["required_if:is_depreciable,on", "nullable", "date"],
            "useful_life_in_years" => ["required_if:is_depreciable,on", "nullable", "integer", "min:1"],
            "end_of_life_date" => ["required_if:is_depreciable,on", "nullable", "date"],

            //misc fields
            "supplier" => ["nullable", "exists:suppliers,id"]
        ]);

        //make the is_depreciable true/false!
        $validated['is_depreciable'] = $request->has('is_depreciable');

        $imagePath = null;
        //store the image in the public folder if uploaded!
        if($request->hasFile('image_path')){
            $imagePath = $request->file('image_path')->store('assets/images', 'public');
        }
        
        Asset::create([
            "asset_code" => $validated['asset_code'],
            "name" => $validated['asset_name'],
            "serial_name" => $validated['serial_name'],
            "category_id" => $validated['category'],
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

    public function updateAsset(Request $request, $id){
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            //general fields
            "asset_code" => ["required", Rule::unique('assets', 'asset_code')->ignore($id)],
            "asset_name" => ["required", "string", "max:100"],
            "serial_name" => ["nullable", "string", "max:100"],
            "category" => ["required", "exists:categories,id"],
            "subcategory" => ["nullable", "exists:sub_categories,id"],
            "description" => ["nullable", "string", "max:255"],
            "image_path" => ["nullable", "image", "mimes:jpeg,png,jpg,gif", "max:2048"],

            //assignment fields
            "department" => ["required", "exists:departments,id"],
            "custodian" => ["nullable", "exists:employees,id"],

            //financial fields!!
            "is_depreciable" => ["nullable"],
            "cost" => ["required_if:is_depreciable,on", "nullable", "numeric", "min:0"],
            "salvage_value" => ["required_if:is_depreciable,on", "nullable", "numeric", "min:0"],
            "acquisition_date" => ["required_if:is_depreciable,on", "nullable", "date"],
            "useful_life_in_years" => ["required_if:is_depreciable,on", "nullable", "integer", "min:1"],
            "end_of_life_date" => ["required_if:is_depreciable,on", "nullable", "date"],

            //misc fields
            "supplier" => ["nullable", "exists:suppliers,id"]
        ]);

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
        $validated = $request->validate([
            //checks if the enum value exists
            "disposal_method" => ["required", new Enum(DisposalMethods::class)],
            "reason" => ["nullable", "string", "max:255"]
        ]);

        //this make is so the DB updates in one go and if anything fails then everything fails!!
        try{
            DB::transaction(function() use($validated, $id){
                $asset = Asset::findOrFail($id);

                if($asset->status === AssetStatus::DISPOSED){
                    return redirect()->route("assets.index")->with('error', 'This asset is already disposed!');
                }

                //first create workorder!
                $workorder = Workorder::create([
                    "completed_by" => auth()->user()->id,
                    "start_date" => now(),
                    "end_date" => now(),
                    "priority_level" => PriorityLevel::HIGH,
                    "type" => WorkorderType::DISPOSAL,
                    "status" => WorkorderStatus::COMPLETED
                ]);

                //create disposal workorder
                DisposalWorkorder::create([
                    "workorder_id" => $workorder->id,
                    "asset_id" => $asset->id,
                    "disposal_method" => $validated['disposal_method'],
                    "disposal_date" => now(),
                    "reason" => $validated['reason'] ?? null
                ]);

                $asset->status = AssetStatus::DISPOSED;
                $asset->save();
                $asset->delete();
            });
        }catch(\Exception $e){
            return redirect()->route("assets.index")->with('error', 'Something went wrong!');
        }

        return redirect()->route("assets.index")->with('success', 'Asset disposed Successfully!');
    }
}