<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Enums\ActivityAction;
use App\Enums\ActivityModule;

class EmployeesController extends Controller
{
    //
    public function getEmployees(Request $request){

        // Get employees with their department info

        $query = Employee::with(['department'])->withCount('user');

        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $departmentid = auth()->user()->employee->department_id;
            $query->where('department_id', $departmentid);
        }

        if(request('search')){
            $search = $request->input("search");
            $query->search($search);
        }

        if(request('show_archived')){
            $query->onlyTrashed();
        }

        $role = auth()->user()->getRoleNames()->first();

        $desc = $role === "System Supervisor" ? "View, add, and manage employees and their assets" : "View employees and their assigned assets";

        $employees = $query->paginate(5);
        $departments = Department::orderBy('name')->pluck('name', 'id');

        $columns = ["","Name", "Department", "Custodian", "Maintenance Crew" ,"Actions"];
        return view("pages.employees.index-employees", compact('employees', 'columns', 'departments', 'desc'));
    }

    public function getEmployee($id){
        $employee = Employee::withTrashed()->with('department', 'assets')->findOrFail($id);

        $columns = ["Asset Code", "Asset Name", "Serial Name", "Department", "Category", "Subcategory", "Status"];

        return view('pages.employees.show-employee', compact('employee', 'columns'));
    }

    public function storeEmployees(Request $request){
        $request->merge(['is_maintenance' => $request->has('is_maintenance')]);

        $validated = $request->validate([
            "name"=> ["required", "max:100", "string"],
            "department"=> ["required", "exists:departments,id"],
            "is_maintenance" => ["required", "boolean"]
        ]);

        Employee::create([
            'name' => $validated['name'],
            'department_id' => $validated['department'],
            'is_maintenance' => $validated['is_maintenance'],
        ]);

        ActivityLog::log(ActivityModule::EMPLOYEE, ActivityAction::CREATED, "Created employee: " . $validated['name']);

        return redirect()->route('employees.index')->with('success', 'Employee successfully created!');
    }

    public function updateEmployee(Request $request, $id){
        $request->merge(['is_maintenance' => $request->has('is_maintenance')]);

        $validated = $request->validate([
            "name"=> ["required", "max:100", "string"],
            "department"=> ["required", "exists:departments,id"],
            "is_maintenance" => ["required", "boolean"]
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            'name' => $validated['name'],
            'department_id' => $validated['department'],
            'is_maintenance' => $validated['is_maintenance'],
        ]);

        ActivityLog::log(ActivityModule::EMPLOYEE, ActivityAction::UPDATED, "Updated employee: " . $validated['name']);

        return redirect()->route('employees.index')->with('success', 'Employee edited successfully!');
    }

    public function deleteEmployee($id){
        $employee = Employee::findOrFail($id);

        if($employee->assets()->exists()){
            return redirect()->back()->with('error', 'Employee has assigned assets. Please unassign them first.');
        }

        ActivityLog::log(ActivityModule::EMPLOYEE, ActivityAction::ARCHIVED, "Archived employee: " . $employee->name);

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee archived successfully!');
    }

    public function restoreEmployee($id){
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        ActivityLog::log(ActivityModule::EMPLOYEE, ActivityAction::RESTORED, "Restored employee: " . $employee->name);

        return redirect()->back()->with('success', 'Employee restored successfully!');
    }
}
