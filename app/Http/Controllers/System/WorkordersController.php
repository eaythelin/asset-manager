<?php

namespace App\Http\Controllers\System;

use App\Enums\DisposalMethods;
use App\Enums\MaintenanceType;
use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use App\Enums\AssetStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkorderValidation;
use App\Models\DisposalWorkorder;
use App\Models\ServiceWorkorder;
use App\Models\RequisitionWorkorder;
use Illuminate\Http\Request;
use App\Models\Workorder;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Exception;

class WorkordersController extends Controller
{
    public function getWorkOrders(Request $request){
        $query = Workorder::with('request', 'disposalWorkOrder', 'serviceWorkorder', 'requisitionWorkorder')->where('is_direct', false);

        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        }

        $columns = ["Workorder Code", "Request Code", "Type", "Priority", "Start Date", "End Date", "Status", "Actions"];

        $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'overdue', 'completed', 'cancelled')");
        $workorders = $query->paginate(5);
        $workorders->each(fn($workorders) => $workorders->check_status);
        return view('pages.workorders.index-workorders', compact('workorders', 'columns'));
    }

    public function getEditWorkorder($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $priorities = PriorityLevel::cases();
        $disposalMethods = DisposalMethods::cases();
        $suppliers = Supplier::orderBy('name')->pluck('name','id');
        $maintenanceTypes = MaintenanceType::cases();
        $employees = Employee::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->pluck('full_name', 'id');
        $relations = match($workorder->type){
            WorkorderType::REQUISITION => ['requisitionWorkorder'],
            WorkorderType::SERVICE => ['serviceWorkorder'],
            WorkorderType::DISPOSAL => ['disposalWorkorder'],
            default => [],
        };
        $workorder->load($relations);

        return view('pages.workorders.edit-workorder', compact('workorder', 'priorities', 'disposalMethods','suppliers','maintenanceTypes','employees'));
    }

    public function updateWorkorder(WorkorderValidation $request, $id){
        $validated = $request->validated();
        $workorder = Workorder::findOrFail($id);

        try {
            DB::transaction(function() use ($workorder, $validated, $request){
                $workorder->update([
                    "priority_level" => $validated["priority_level"],
                    "start_date" => $validated["start_date"],
                    "end_date" => $validated["end_date"],
                ]);

                if($workorder->workorder_type === WorkorderType::DISPOSAL){
                    $disposalWO = DisposalWorkorder::findOrFail($request->sub_wo_id);

                    $disposalWO->update([
                        "quantity" => $validated["quantity"],
                        "disposal_method" => $validated["disposal_method"],
                        "disposal_date" => $validated["disposal_date"],
                        "reason" => $validated["reason"] ?? null,
                    ]);
                }elseif($workorder->workorder_type === WorkorderType::SERVICE){
                    $serviceWO = ServiceWorkorder::findOrFail($request->sub_wo_id);

                    $serviceWO->update([
                        "maintenance_type" => $validated["maintenance_type"],
                        "instructions" => $validated["instructions"] ?? null,
                        "accomplishment_report" => $validated["accomplishment_report"] ?? null,
                        "assigned_to" => $validated["assigned_to"] ?? null,
                        "estimated_hours" => $validated["estimated_hours"] ?? null,
                        "subcontractor_name" => $validated["subcontractor_name"] ?? null,
                        "subcontractor_details" => $validated["subcontractor_details"] ?? null,
                        "cost" => $validated["cost"]
                    ]);
                }elseif($workorder->workorder_type === WorkorderType::REQUISITION){
                    $requisitionWO = RequisitionWorkorder::findOrFail($request->sub_wo_id);

                    $requisitionWO->update([
                        "asset_name" => $request->is_new_asset ? $validated["asset_name"]: null,
                        "acquisition_date"=> $request->is_new_asset ? $validated["acquisition_date"] : null,
                        "estimated_cost" => $request->is_new_asset ? $validated["estimated_cost"] : 0,
                        "supplier_id" => $validated["supplier_id"] ?? null,
                        "description" => $validated["description"] ?? null
                    ]);
                }
            });
            return redirect()->route("workorders.index")->with("success","Workorder edited successfully!");
        }catch (Exception $e){
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function startWO($id){
        $workorder = Workorder::findOrFail($id);

        if($workorder->start_date === null && $workorder->end_date === null){
            return redirect()->back()->with('error','Date fields are not filled!');
        };

        $workorder->status = WorkorderStatus::IN_PROGRESS->value;
        $workorder->save();

        return back()->with('success','Workorder status changed successfully!');
    }

    public function cancelWO($id){
        try{
            DB::transaction(function () use ($id){
                $workorder = Workorder::findOrFail($id);
                $workorder->update([
                    'status' => WorkorderStatus::CANCELLED
                ]);

                if($workorder->workorder_type === WorkorderType::SERVICE){
                    $asset = $workorder->serviceWorkorder->asset;
                    $asset->update([
                        'status' => AssetStatus::ACTIVE->value
                    ]);
                };
            });

            return redirect()->route("workorders.index")->with('success', 'Workorder cancelled successfully!');

        }catch (Exception $e){
            return redirect()->route("workorders.index")->with('error', 'Something went wrong!');
        }
    }

    public function completeWO($id){
        try{
            DB::transaction(function() use($id){
                $workorder = Workorder::findOrFail($id);
                $workorder->update([
                    "completed_by" => auth()->user()->id,
                    "status" => WorkorderStatus::COMPLETED->value
                ]);

                if($workorder->workorder_type === WorkorderType::DISPOSAL){
                    $disposalWO = $workorder->disposalWorkOrder;
                    $asset = $disposalWO->asset;

                    $remaining = $asset->quantity - $disposalWO->quantity;
                    if($remaining <= 0){
                        $asset->status = AssetStatus::DISPOSED;
                        $asset->save();
                        $asset->delete();
                    }else{
                        $asset->quantity = $remaining;
                        $asset->save();
                    }
                }elseif($workorder->workorder_type === WorkorderType::REQUISITION){
                    $requisitionWO = $workorder->requisitionWorkOrder;
                    $requestModel = $workorder->request;

                    if($requisitionWO->asset_name !== null){
                        $count = Asset::withTrashed()->count();
                        $nextCode = 'AST-'.($count + 1);

                        $asset = Asset::create([
                            'asset_code' => $nextCode,
                            'name' => $requisitionWO->asset_name,
                            'category_id'=> $requestModel->category_id,
                            'sub_category_id' => $requestModel->sub_category_id ?? null,
                            'quantity' => $requestModel->quantity,
                            'acquisition_date' => $requisitionWO->acquisition_date,
                            'supplier_id' => $requisitionWO->supplier_id ?? null,
                            'description' => $requisitionWO->description ?? null,
                            'status' => AssetStatus::ACTIVE,
                            'department_id' => $requestModel->department_id,
                            'cost' => $requisitionWO->estimated_cost
                        ]);

                        $requisitionWO->update(['asset_id' => $asset->id]);
                    }else{
                        $asset = $requisitionWO->asset;

                        $asset->update([
                            'quantity' => $asset->quantity + $requestModel->quantity
                        ]);
                    }
                }elseif($workorder->workorder_type === WorkorderType::SERVICE){
                    $serviceWO = $workorder->serviceWorkorder;
                    $asset = $serviceWO->asset;

                    $asset->update([
                        'status' => AssetStatus::ACTIVE->value
                    ]);
                }
            });

            return redirect()->route("workorders.index")->with('success', 'Workorder completed successfully!');

        }catch (Exception $e){
            return redirect()->route("workorders.index")->with('error', 'Something went wrong!');
        };
    }

    public function getWOPage($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $relations = match($workorder->workorder_type){
            WorkorderType::REQUISITION => ['requisitionWorkorder.asset'],
            WorkorderType::SERVICE => ['serviceWorkorder.asset'],
            WorkorderType::DISPOSAL => ['disposalWorkorder' => function($q){
                $q->with(['asset' => function($q2){
                    $q2->withTrashed();
                }]);
            }],
            default => [],
        };
        $workorder->load($relations);

        return view('pages.workorders.show-workorder', compact('workorder'));
    }
}
