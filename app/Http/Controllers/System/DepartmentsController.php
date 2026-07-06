<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;
use App\Enums\ActivityAction;
use App\Enums\ActivityModule;

class DepartmentsController extends Controller
{
    //
    public function getDepartments(Request $request){
        // get the search value if its provided!!
        $search = $request->input("search");
        $departments = Department::search($search)->paginate(5);

        $columns = ["","Department Name", "Description", "Actions"];
        return view("pages.departments", compact('departments', "columns"));
    }

    public function storeDepartments(Request $request){
        $validated = $request->validate([
            "name"=>["required", "string","unique:departments,name", "max:100"],
            "description"=>["nullable","string", "max:255"]
        ]);
        //create the department if all the rules pass
        Department::create($validated);
        ActivityLog::log(ActivityModule::DEPARTMENT, ActivityAction::CREATED, "Created department: " . $validated['name']);

        return redirect()->route('department.index')->with('success','Department successfully created!');
    }

    public function updateDepartment(Request $request, $id){
        //rule is: This name must be unique, except for the department that is currently being edited
        $validated = $request->validate([
            "name"=>["required", "string", Rule::unique('departments', 'name')->ignore($id), "max:100"],
            "description"=>["nullable","string", "max:255"]
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);
        ActivityLog::log(ActivityModule::DEPARTMENT, ActivityAction::UPDATED, "Updated department: " . $validated['name']);

        return redirect()->route('department.index')->with('success', 'Department edited successfully!');
    }

    public function deleteDepartment($id){
        //throws 404 error if ID doesnt exist
        $department = Department::findOrFail($id);
        if($department->employees()->exists()){
            return redirect()->back()->with('error', 'Department have existing employees!');
        }

        $department->delete();
        ActivityLog::log(ActivityModule::DEPARTMENT, ActivityAction::DELETED, "Deleted department: " . $department->name);

        return redirect()->route('department.index')->with('success', 'Department deleted successfully!');
    }

    public function quickStore(Request $request){
        $validated = $request->validate([
            "name"=>["required", "string","unique:departments,name", "max:100"]
        ]);

        $department = Department::create(['name' => $validated['name']]);
        ActivityLog::log(ActivityModule::DEPARTMENT, ActivityAction::CREATED, "Quick created department: " . $validated['name']);

        return response()->json([
            'id' => $department->id,
            'name' => $department->name,
        ]);
    }
}
