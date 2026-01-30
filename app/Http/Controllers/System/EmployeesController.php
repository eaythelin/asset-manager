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

        $role = auth()->user()->getRoleNames()->first();

        $desc = $role === "System Supervisor" ? "View, add, and manage employees and their assets" : "View employees and their assigned assets";

        $employees = $query->paginate(5);
        $departments = Department::orderBy('name')->pluck('name', 'id');

        $columns = ["","Name", "Department", "Custodian", "Actions"];
        return view("pages.employees.index-employees", compact('employees', 'columns', 'departments', 'desc'));
    }

    public function getEmployee($id){
        $employee = Employee::with('department', 'assets')->findOrFail($id);

        $columns = ["Asset Code", "Asset Name", "Serial Name", "Department", "Category", "Subcategory", "Status"];
        
        return view('pages.employees.show-employee', compact('employee', 'columns'));
    }

    public function storeEmployees(Request $request){
        $validated = $request->validate([
            "first_name"=> ["required", "max:100", "string"],
            "last_name"=> ["required", 'max:100', "string"],
            "department_id"=> ["required", "exists:departments,id"]
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee successfully created!');
    }

    public function updateEmployee(Request $request, $id){
        $validated = $request->validate([
            "first_name"=> ["required", "max:100", "string"],
            "last_name"=> ["required", 'max:100', "string"],
            "department_id"=> ["required", "exists:departments,id"]
        ]);

        //'regex:/^[a-zA-Z\s\'-]+$/' regex maybe? in the future??

        $employee = Employee::findOrFail($id);
        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee edited successfully!');
    }

    public function deleteEmployee($id){
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
