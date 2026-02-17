<?php

namespace App\Http\Controllers\System;

use App\Enums\AssetStatus;
use App\Enums\RequestTypes;
use App\Enums\ServiceTypes;
use App\Http\Controllers\Controller;
use App\Models\RequisitionWorkorder;
use App\Models\RequestFile;
use App\Models\ServiceWorkorder;
use Illuminate\Http\Request;
use Auth;
use App\Models\Request as RequestModel;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Workorder;
use App\Models\DisposalWorkorder;
use Illuminate\Validation\Rules\Enum;
use App\Enums\RequestStatus;
use App\Enums\WorkorderType;
use Illuminate\Support\Facades\DB;


class RequestsController extends Controller
{
    public function getRequests(){

        $role = Auth::user()->getRoleNames()->first();

        $query = RequestModel::with('category', 'subCategory', 'requestedBy', 'approvedBy', 'asset');

        if(auth()->user()->getRoleNames()->contains('Department Head')){
            $userID = auth()->user()->id;
            $query->where('requested_by', $userID);
        }elseif(auth()->user()->getRoleNames()->contains('General Manager')){
            $query->where('status', '!=', RequestStatus::DRAFT->value);
        }else{
            $query->where('status', '!=', RequestStatus::DRAFT->value);
        }

        if(auth()->user()->getRoleNames()->contains('General Manager')){
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'declined', 'cancelled')");
        }

        $query->latest('date_requested');

        $requests = $query->paginate(5);

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

        return view('pages.requests.index-requests', compact('desc', 'requests', 'columns', 'centeredColumns'));
    }

    public function getCreateRequest(){
        $count = RequestModel::withTrashed()->count();
        $nextCode = 'REQ-'.($count + 1);

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
        $rules = [
            "request_code" => ["required", "unique:requests"],
            "type" => ["required", new Enum(RequestTypes::class)],
            "description" => ["nullable", "string", "max:500"],

            //filez
            "attachments" => ["nullable", "array", "max:5"],
            "attachments.*" => ["file", "max:10240", "mimes:jpg,jpeg,png,pdf,doc,docx"]
        ];

        $validated = $request->validate(
            match($request->type){
            RequestTypes::REQUISITION->value => array_merge($rules, [
                "asset_name" => ["required", "max:100", "string"],
                "category" => ["required", "exists:categories,id"],
                "subcategory" => ["nullable", "exists:sub_categories,id"],
            ]),
            RequestTypes::SERVICE->value => array_merge($rules,[
                "asset_id" => ["required", "exists:assets,id"],
                "service_type" => ["required", new Enum(ServiceTypes::class)]
            ]),
            RequestTypes::DISPOSAL->value => array_merge($rules, [
                "asset_id" => ["required", "exists:assets,id"]
            ]),
            default => $rules
        });

        $requestModel = RequestModel::create([
            'request_code' => $validated['request_code'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'requested_by' => auth()->id(),
            'date_requested' => now(),
            
            // Requisition fields (nullable)
            'asset_name' => $validated['asset_name'] ?? null,
            'category_id' => $validated['category'] ?? null,
            'subcategory_id' => $validated['subcategory'] ?? null,
            
            // Service/Disposal fields (nullable)
            'asset_id' => $validated['asset_id'] ?? null,
            'service_type' => $validated['service_type'] ?? null,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('request-attachments');
                
                RequestFile::create([
                    'request_id' => $requestModel->id,
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName()
                ]);
            }
        }

        return redirect()->route('requests.index')->with('success', 'Request Successfully Created!');
    }

    public function submitRequest($id){
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->status = RequestStatus::PENDING;
        $requestModel->save();

        return redirect()->route('requests.index')->with('success', 'Request submitted!');
    }

    public function cancelRequest($id){
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->status = RequestStatus::CANCELLED;
        $requestModel->save();
        $requestModel->delete();

        return redirect()->route('requests.index')->with('success', 'Request cancelled!');
    }

    public function approveRequest($id){
        try {
            DB::transaction(function() use($id){
                $requestModel = RequestModel::findOrFail($id);
                $requestModel->update([
                    "status" => RequestStatus::APPROVED,
                    "handled_by" => auth()->id(),
                    "date_approved" => now()
                ]);
                
                if($requestModel->type === RequestTypes::REQUISITION){
                    $count = Workorder::withTrashed()->where('type', WorkorderType::REQUISITION)->count();
                    $nextCode = 'WO-REQ-'.($count + 1);
                    $workorder = Workorder::create([
                        "workorder_code" => $nextCode,
                        "type" => WorkorderType::REQUISITION,
                        "request_id" => $requestModel->id
                    ]);

                    RequisitionWorkorder::create([
                        "workorder_id" => $workorder->id,
                        "asset_name" => $requestModel->asset_name
                    ]);
                }elseif($requestModel->type === RequestTypes::SERVICE){
                    $count = Workorder::withTrashed()->where('type', WorkorderType::SERVICE)->count();
                    $nextCode = 'WO-SER-'.($count + 1);
                    $workorder = Workorder::create([
                        "workorder_code" => $nextCode,
                        "type" => WorkorderType::SERVICE,
                        "request_id" => $requestModel->id
                    ]);

                    ServiceWorkorder::create([
                        "workorder_id" => $workorder->id,
                        "asset_id" => $requestModel->asset_id,
                        "service_type" => $requestModel->service_type->value
                    ]);

                    Asset::find($requestModel->asset_id)->update([
                        'status' => AssetStatus::UNDER_SERVICE
                    ]);
                    
                }elseif($requestModel->type === RequestTypes::DISPOSAL){
                    $count = Workorder::withTrashed()->where('type', WorkorderType::DISPOSAL)->count();
                    $nextCode = 'WO-DIS-'.($count + 1);
                    $workorder = Workorder::create([
                        "workorder_code" => $nextCode,
                        "type" => WorkorderType::DISPOSAL,
                        "request_id" => $requestModel->id
                    ]);

                    DisposalWorkorder::create([
                        "workorder_id" => $workorder->id,
                        "asset_id" => $requestModel->asset_id 
                    ]);
                }
            });

            return redirect()->route('requests.index')->with('success', 'Request successfully approved!');
            
        } catch (\Exception $e) {
            return redirect()->route("requests.index")->with('error', 'Something went wrong!');
        }
    }

    public function declineRequest($id){
        $requestModel = RequestModel::findOrFail($id);
        $requestModel->update([
            "status" => RequestStatus::DECLINE,
            "handled_by" => auth()->id(),
            "date_approved" => now()
        ]);

        return redirect()->route('requests.index')->with('success', 'Request successfully declined!');
    }
}
