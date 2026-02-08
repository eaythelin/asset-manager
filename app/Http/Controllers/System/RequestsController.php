<?php

namespace App\Http\Controllers\System;

use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Request as RequestModel;
use App\Models\Asset;
use App\Models\Category;


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

    public function getCreateRequest(){
        $latestRequest = RequestModel::withTrashed()->latest('id')->first();
        $nextCode = $latestRequest ? 'REQ-' . ($latestRequest->id + 1): 'REQ-1';

        $requestTypes = RequestTypes::cases();
        $assets = Asset::orderBy('asset_code')->get();
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $serviceTypes = ServiceTypes::cases();

        return view('pages.requests.create-request', compact('nextCode', 'requestTypes', 'assets', 'categories', 'serviceTypes'));
    }

    public function getSubcategories(Category $categoryID){
        return response()->json($categoryID->subCategories);
    }

    public function storeRequest(Request $request){
        
    }
}
