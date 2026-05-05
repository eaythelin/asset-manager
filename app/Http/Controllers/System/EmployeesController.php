<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

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

        $columns = ["","Name", "Department", "Custodian", "Actions"];
        return view("pages.employees.index-employees", compact('employees', 'columns', 'departments', 'desc'));
    }

    public function getEmployee($id){
        $employee = Employee::withTrashed()->with('department', 'assets')->findOrFail($id);

        $columns = ["Asset Code", "Asset Name", "Serial Name", "Department", "Category", "Subcategory", "Status"];
        
        return view('pages.employees.show-employee', compact('employee', 'columns'));
    }

    public function storeEmployees(Request $request){
        $validated = $request->validate([
            "first_name"=> ["required", "max:100", "string"],
            "last_name"=> ["required", 'max:100', "string"],
            "department"=> ["required", "exists:departments,id"]
        ]);

        Employee::create([
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "department_id" => $validated["department"]
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee successfully created!');
    }

    public function updateEmployee(Request $request, $id){
        $validated = $request->validate([
            "first_name"=> ["required", "max:100", "string"],
            "last_name"=> ["required", 'max:100', "string"],
            "department"=> ["required", "exists:departments,id"]
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "department_id" => $validated["department"]
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee edited successfully!');
    }

    public function deleteEmployee($id){
        $employee = Employee::findOrFail($id);

        if($employee->assets()->exists()){
            return redirect()->back()->with('error', 'Employee has assigned assets. Please unassign them first.');
        }

        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee archived successfully!');
    }

    public function restoreEmployee($id){
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()->back()->with('success', 'Employee restored successfully!');
    }
}