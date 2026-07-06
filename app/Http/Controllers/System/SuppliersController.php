<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Enums\ActivityAction;
use App\Enums\ActivityModule;

class SuppliersController extends Controller
{
    public function getSuppliers(Request $request){
        $search = $request->input('search');
        $suppliers = Supplier::search($search)->paginate(5);
        $columns = ["", "Name", "Contact Person", "Email", "Phone Number", "Address", "Actions"];
        return view("pages.suppliers", compact('suppliers', 'columns'));
    }

    public function storeSupplier(Request $request){
        $validated = $request->validate([
            "name" => ["required", "string", "max:100"],
            "contact_person" => ["nullable", "string", "max:100"],
            "email" => ["nullable", "email", "string", "max:100"],
            "phone_number" => ["nullable", "regex:/^\+?[0-9]+$/", "min:11", "max:20"], //Matches 09171234567 and +639171234567
            "address" => ["nullable", "string", "max:255"]
        ]);

        Supplier::create($validated);
        ActivityLog::log(ActivityModule::SUPPLIER, ActivityAction::CREATED, "Created supplier: " . $validated['name']);

        return back()->with("success", "Supplier successfully created!");
    }

    public function updateSupplier(Request $request, $id){
        $validated = $request->validate([
            "name" => ["required", "string", "max:100"],
            "contact_person" => ["nullable", "string", "max:100"],
            "email" => ["nullable", "email", "string", "max:100"],
            "phone_number" => ["nullable", "regex:/^\+?[0-9]+$/", "min:11", "max:20"],
            "address" => ["nullable", "string", "max:255"]
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);
        ActivityLog::log(ActivityModule::SUPPLIER, ActivityAction::UPDATED, "Updated supplier: " . $validated['name']);

        return back()->with("success", "Supplier updated successfully!");
    }

    public function deleteSupplier($id){
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        ActivityLog::log(ActivityModule::SUPPLIER, ActivityAction::DELETED, "Deleted supplier: " . $supplier->name);

        return redirect()->route('suppliers.index')->with("success", "Supplier deleted successfully!");
    }

    public function quickStore(Request $request){
        $validated = $request->validate([
            "name" => ["required", "string", "max:100"]
        ]);

        $supplier = Supplier::create(['name' => $validated['name']]);
        ActivityLog::log(ActivityModule::SUPPLIER, ActivityAction::CREATED, "Quick created supplier: " . $validated['name']);

        return response()->json([
            'id' => $supplier->id,
            'name' => $validated['name']
        ]);
    }
}
