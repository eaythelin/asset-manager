<?php

namespace App\Http\Controllers\System;

use App\Enums\AssetStatus;
use App\Enums\DisposalConditions;
use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
use App\Http\Controllers\Controller;
use App\Models\RequisitionWorkorder;
use App\Models\RequestFile;
use App\Models\ServiceWorkorder;
use App\Models\Department;
use Illuminate\Http\Request;
use Auth;
use App\Models\Request as RequestModel;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Workorder;
use App\Models\DisposalWorkorder;
use App\Enums\RequestStatus;
use App\Enums\WorkorderType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RequestValidation;

class RequestsController extends Controller
{
    public function getRequests(Request $request){

        $role = Auth::user()->getRoleNames()->first();

        $query = RequestModel::with('requestedBy', 'approvedBy', 'asset');

        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        }

        if($role === 'Department Head'){
            $query->where('requested_by', auth()->id());
        } elseif($role === 'General Manager'){
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'cancelled')");
        }

        $query->latest('created_at');

        $requests = $query->paginate(5);

        $desc = match($role) {
            'Department Head' => 'View and manage your maintenance requests',
            'General Manager' => 'View and approve/decline requests',
            default => 'View requests',
        };

        $columns = match($role) {
            'Department Head' => ["Control No.", "Asset", "Type", "Description", "Date Requested", "Status", "Actions"],
            'General Manager', 'System Supervisor' => ["Control No.", "Requested By", "Asset", "Type", "Date Requested", "Status", "Actions"],
            default => [],
        };

        $centeredColumns = match($role){
            'Department Head' => [0,5,6],
            'General Manager', 'System Supervisor' => [0,5,6],
            default => [],
        };

        return view('pages.requests.index-requests', compact('desc', 'requests', 'columns', 'centeredColumns'));
    }

    public function getCreateRequest(){
        $year = now()->year;
        $latest = RequestModel::whereYear('created_at', $year)->latest()->first();
        $count = $latest ? (int) substr($latest->control_number, -4) + 1 : 1;
        $controlNumber = $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $requestTypes = RequestTypes::cases();
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $assets = Asset::where('department_id', auth()->user()->employee->department_id)
            ->orderBy('asset_code')
            ->get();
        return view('pages.requests.create-request', compact('controlNumber', 'requestTypes', 'assets', 'departments'));
    }

    public function storeRequest(RequestValidation $request){
        $validated = $request->validated();

        RequestModel::create([
            "control_number" => $validated["control_number"],
            "description" => $validated["description"],
            "request_type" => $validated["request_type"],
            "asset_id" => $validated["asset"],
            "requested_by" => $validated["requisitioner"],
            "department_id" => $validated["department"],
        ]);

        return redirect()->route("requests.index")->with("success","Request successfully created!");
    }

    public function getEditRequest($id){
        $requestModel = RequestModel::with(['department','asset','requestedBy'])->findOrFail($id);
        $requestTypes = RequestTypes::cases();
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $assets = Asset::where('department_id', auth()->user()->employee->department_id)
            ->orderBy('asset_code')
            ->get();
        return view('pages.requests.edit-request', compact('requestModel', 'requestTypes','assets', 'departments'));
    }

    public function updateRequest(RequestValidation $request, $id){
        $validated = $request->validated();

        $requestModel = RequestModel::findOrFail($id);

        $requestModel->update([
            "description" => $validated["description"],
            "request_type" => $validated["request_type"],
            "asset_id" => $validated["asset"],
            "department_id" => $validated["department"],
        ]);

        return redirect()->route("requests.index")->with("success","Request successfully edited!");
    }

    public function getPageRequest($id){
        $requestModel = RequestModel::with(['asset','department', 'requestedBy', 'approvedBy'])->findOrFail($id);
        return view('pages.requests.show-request', compact('requestModel'));
    }

    public function cancelRequest($id){
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->status = RequestStatus::CANCELLED;
        $requestModel->save();

        return redirect()->route('requests.index')->with('success', 'Request cancelled!');
    }

    public function approveRequest($id){
        try {
            DB::transaction(function() use($id){
                $requestModel = RequestModel::findOrFail($id);
                $requestModel->update([
                    "status" => RequestStatus::APPROVED->value,
                    "approved_by" => auth()->id(),
                    "approved_at" => now()
                ]);

                Asset::findOrFail($requestModel->asset_id)->update([
                    'status' => $requestModel->request_type->value
                ]);
            });

            return redirect()->route('requests.index')->with('success', 'Request Successfully Approved!');

        } catch (\Exception $e) {
            return redirect()->route("requests.index")->with('error', 'Something went wrong!');
        }
    }

    public function declineRequest($id){
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update([
            "status" => RequestStatus::REJECTED,
        ]);

        return redirect()->route('requests.index')->with('success', 'Request Successfully Declined!');
    }
}
