<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    //
    public function getUsers(Request $request){
        $query = User::with('roles', 'employee');

        if(request('show_deleted')){
            //show only soft deleted users!
            $query->onlyTrashed();
        }

        //search!
        if(request('search')){
            $search = $request->input("search");

            $query->search($search);
        }

        $users = $query->paginate(5);

        $employees = Employee::select('id', 'first_name', 'last_name')
            ->whereDoesntHave('user', function($query){
                $query->withTrashed();
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->pluck('full_name', 'id');

        $roles = Role::orderBy('name')->pluck('name', 'id');
        
        $columns = ["", "Name", "Email", "Status","Role", "Actions"];
        return view("pages.users", compact('employees', 'columns', 'users', 'roles'));
    }

    public function storeUser(Request $request){
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255','unique:users,email'],
            'password' => ['required', 'min:8', 'string'],
            'employee_id' => ['required', 'exists:employees,id'],
            'role_id' => ['required', 'exists:roles,id']
        ]);

        //get employee info
        $employee = Employee::findOrFail($validated['employee_id']);

        $username = $employee->first_name . ' ' . $employee->last_name;

        $user = User::create([
            'name' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'employee_id' => $validated['employee_id']
        ]);

        //get role info
        $role = Role::findOrFail($validated['role_id']);

        $user->assignRole($role->name);

        return redirect()->route('users.index')->with('success', 'System User successfully created!');
    }

    public function updateUser(Request $request, $id){
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'employee_id' => ['required', Rule::unique('users', 'employee_id')->ignore($id)],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'min:8', 'string', 'confirmed']
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
        ]);

        //this runs only if the password field is filled
        if($request->filled('password')) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        $currentRoleID = $user->roles->first()->id ?? null;

        //Only update role if not editing their own account!!
        //prevent role change if editing yourself AND the role is different
        if(auth()->id() === $user->id && $validated['role_id'] != $currentRoleID){
            return redirect()->route('users.index')->with('error', 'You cannot change your own role!');
        }

        $role = Role::findOrFail($validated['role_id']);
        $user->syncRoles($role->name);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function toggleUser($id){
        $user = User::findOrFail($id);

        if(auth()->id()=== $user->id){
            return back()->with('error', 'You cannot change the status of your own account!');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User status updated!');
    }

    public function restoreUser($id){
        $user = User::withTrashed()->findOrFail($id);
        $user->update(['is_active' => true]);
        $user->restore();

        return back()->with('success', 'User restored!');
    }

    public function softDeleteUser($id){
        $user = User::findOrFail($id);

        if(auth()->id()=== $user->id){
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
        }

        $user->update(['is_active' => false]);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User has been successfully deleted!');
    }

    public function forceDelete($id){
        $user = User::withTrashed()->findOrFail($id);

        //NOTE:
        //add prevent force deletion of user if its tied to a record like requests or workorder!!!

        $user->forceDelete();

        return redirect()->route('users.index')->with('success', 'User record has been deleted!');
    }
}