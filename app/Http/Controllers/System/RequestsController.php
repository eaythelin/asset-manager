<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Request as RequestModel;

class RequestsController extends Controller
{
    public function getRequests(){

        $role = Auth::user() -> getRoleNames() -> first();

        $query = RequestModel::with('category', 'subCategory', 'requestedBy', 'approvedBy', 'asset');

        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $userID = auth()->user()->id;
            $query->where('requested_by', $userID);
        }

        $desc = match($role) {
            'Department Head' => 'View and manage your requests',
            'General Manager' => 'View and approve/decline requests',
            default => 'View pending requests',
        };

        $columns = match($role) {
            'Department Head' => ["Request Code", "Asset Name", "Type", "Category", "Date Requested", "Status", "Actions"],
            'General Manager', 'System Supervisor' => ["Request Code", "Requested By", "Asset Name", "Type","Category", "Date Requested", "Status", "Actions"],
            default => [],
        };

        $centeredColumns = match($role){
            'Department Head' => [0,5,6],
            'General Manager', 'System Supervisor' => [0,6,7],
            default => [],
        };

        $requests = $query->paginate(5);

        return view('pages.requests.index-requests', compact('desc', 'requests', 'columns', 'centeredColumns'));
    }
}
