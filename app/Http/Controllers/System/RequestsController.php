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

        $query = RequestModel::with('category', 'subCategory', 'requestedBy', 'approvedBy', 'asset');

        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        }

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
        $assets = Asset::where('department_id', auth()->user()->employee->department_id)
            ->orderBy('asset_code')
            ->get();
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $serviceTypes = ServiceTypes::cases();
        $disposalConditions = DisposalConditions::cases();

        return view('pages.requests.create-request', compact('nextCode', 'requestTypes', 'assets', 'categories', 'serviceTypes', 'disposalConditions'));
    }

    public function getSubcategories(Category $categoryID){
        return response()->json($categoryID->subCategories);
    }

    public function storeRequest(RequestValidation $request){
        $validated = $request->validated();

        $validated['is_new_asset'] = $request->has('is_new_asset');
        
        if($validated['type'] == RequestTypes::DISPOSAL->value || $validated['type'] == RequestTypes::SERVICE->value){
            $asset = Asset::findOrFail($validated['asset_id']);
            if($validated["quantity"] > $asset->quantity){
                return redirect()->back()->with("error", "Request quantity exceeds available quantity!");
            }
        }

        try{
            DB::transaction(function () use ($request, $validated){
                $requestModel = RequestModel::create([
                    'request_code' => $validated['request_code'],
                    'type' => $validated['type'],
                    'quantity'=> $validated['quantity'],
                    'description' => $validated['description'],
                    'requested_by' => auth()->id(),
                    'date_requested' => now(),
                    'department_id' => $validated['department_id'],
                    
                    // Requisition fields (nullable)
                    'asset_name' => $validated['asset_name'] ?? null,
                    'category_id' => $validated['category'] ?? null,
                    'sub_category_id' => $validated['subcategory'] ?? null,
                    'is_new_asset' => $validated['is_new_asset'],
                    
                    // Service/Disposal fields (nullable)
                    'asset_id' => $validated['asset_id'] ?? null,
                    'service_type' => $validated['service_type'] ?? null,
                    'condition' => $validated['condition'] ?? null
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
            });
            return redirect()->route('requests.index')->with('success', 'Request Successfully Created!');
        }catch (\Exception $e){
            return redirect()->route("requests.index")->with('error', 'Something went wrong!');
        }
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
                    $count = Workorder::withTrashed()->where('workorder_type', WorkorderType::REQUISITION)->count();
                    $nextCode = 'WO-REQ-'.($count + 1);
                    $workorder = Workorder::create([
                        "workorder_code" => $nextCode,
                        "workorder_type" => WorkorderType::REQUISITION,
                        "request_id" => $requestModel->id
                    ]);

                    RequisitionWorkorder::create([
                        "workorder_id" => $workorder->id,
                        "asset_name" => $requestModel->is_new_asset ? $requestModel->asset_name : null,
                        "asset_id" => $requestModel->is_new_asset ? null : $requestModel->asset_id,
                    ]);
                }elseif($requestModel->type === RequestTypes::SERVICE){
                    $count = Workorder::withTrashed()->where('workorder_type', WorkorderType::SERVICE)->count();
                    $nextCode = 'WO-SER-'.($count + 1);

                    $workorder = Workorder::create([
                        "workorder_code" => $nextCode,
                        "workorder_type" => WorkorderType::SERVICE,
                        "request_id" => $requestModel->id
                    ]);

                    ServiceWorkorder::create([
                        "workorder_id" => $workorder->id,
                        "asset_id" => $requestModel->asset_id,
                        "service_type" => $requestModel->service_type->value
                    ]);

                    Asset::findOrFail($requestModel->asset_id)->update([
                        'status' => AssetStatus::UNDER_SERVICE
                    ]);
                    
                }elseif($requestModel->type === RequestTypes::DISPOSAL){
                    $count = Workorder::withTrashed()->where('workorder_type', WorkorderType::DISPOSAL)->count();
                    $nextCode = 'WO-DIS-'.($count + 1);
                    $workorder = Workorder::create([
                        "workorder_code" => $nextCode,
                        "workorder_type" => WorkorderType::DISPOSAL,
                        "request_id" => $requestModel->id
                    ]);

                    DisposalWorkorder::create([
                        "workorder_id" => $workorder->id,
                        "asset_id" => $requestModel->asset_id,
                        "quantity" => $requestModel->quantity
                    ]);
                }
            });

            return redirect()->route('requests.index')->with('success', 'Request Successfully Approved!');
            
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

        return redirect()->route('requests.index')->with('success', 'Request Successfully Declined!');
    }

    public function getEditRequest($id){
        $requestModel = RequestModel::with(['category','subCategory','asset','files'])->findOrFail($id);
        $requestTypes = RequestTypes::cases();
        $assets = Asset::where('department_id', auth()->user()->employee->department_id)
            ->orderBy('asset_code')
            ->get();
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $serviceTypes = ServiceTypes::cases();
        $disposalConditions = DisposalConditions::cases();
        return view('pages.requests.edit-request', compact('requestModel', 'requestTypes', 'categories', 'assets', 'serviceTypes', 'disposalConditions'));
    }

    public function updateRequest(RequestValidation $request, $id){
        $validated = $request->validated();
        $validated['is_new_asset'] = $request->has('is_new_asset');
        
        if($validated['type'] == RequestTypes::DISPOSAL->value || $validated['type'] == RequestTypes::SERVICE->value){
            $asset = Asset::findOrFail($validated['asset_id']);
            if($validated["quantity"] > $asset->quantity){
                return redirect()->back()->with("error", "Request quantity exceeds available quantity!");
            }
        }

        $requestModel = RequestModel::findOrFail($id);
        if($request->hasFile('attachments')){
            $existingCount = $requestModel->files()->count();
            $deleteCount = $request->has('delete_files') ? count($request->delete_files) : 0;
            $newfiles = $request->file('attachments');

            if($existingCount - $deleteCount + count($newfiles) > 5){
                return redirect()->route("requests.edit", $requestModel->id)->with('error', 'Maximum of 5 attachment allowed!');
            }
        }

        try{
            DB::transaction(function() use($id, $validated,$request,$requestModel){

                $requestModel->update([
                    'request_code' => $validated['request_code'],
                    'type' => $validated['type'],
                    'quantity'=> $validated['quantity'],
                    'description' => $validated['description'],
                    'is_new_asset' => $validated['is_new_asset'],
                    
                    // Requisition fields (nullable)
                    'asset_name' => $validated['asset_name'] ?? null,
                    'category_id' => $validated['category'] ?? null,
                    'sub_category_id' => $validated['subcategory'] ?? null,
                    
                    // Service/Disposal fields (nullable)
                    'asset_id' => $validated['asset_id'] ?? null,
                    'service_type' => $validated['service_type'] ?? null,
                    'condition' => $validated['condition'] ?? null,
                ]);

                if($request->has('delete_files')){
                    foreach($request->delete_files as $fileID){
                        $file = RequestFile::findOrFail($fileID);
                        Storage::delete($file->file_path);
                        $file->delete();
                    }
                }

                if($request->has('attachments')){
                    $existingCount = $requestModel->files()->count();
                    $newfiles = $request->file('attachments');

                    if($existingCount + count($newfiles) > 5){
                        return redirect()->route("requests.index")->with('error', 'Maximum of 5 attachment allowed!');
                    }
                    foreach($request->file('attachments') as $file){
                        
                        $path = $file->store('request-attachments');
                        RequestFile::create([
                            'request_id' => $requestModel->id,
                            'file_path' => $path,
                            'file_type' => $file->getMimeType(),
                            'original_name' => $file->getClientOriginalName()
                        ]);
                    }
                }
            });
            return redirect()->route('requests.index')->with('success', 'Request edited successfully!');
        }catch(\Exception $e){
            return redirect()->route("requests.index")->with('error', 'Something went wrong!');
        }
    }

    public function serveAttachments($id){
        $file = RequestFile::findOrFail($id);
        return Storage::response($file->file_path);
    }

    public function getPageRequest($id){
        $requestModel = RequestModel::with(['category','subCategory','asset','files'])->findOrFail($id);
        $requestTypes = RequestTypes::cases();
        $requestStatus = RequestStatus::cases();
        return view('pages.requests.show-request', compact('requestModel','requestTypes','requestTypes'));
    }
}