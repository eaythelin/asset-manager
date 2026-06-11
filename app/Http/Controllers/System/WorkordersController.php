<?php

namespace App\Http\Controllers\System;

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
use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Exception;

class WorkordersController extends Controller
{
    public function getWorkOrders(Request $request){
        $query = Workorder::with('request');

        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        }

        $columns = ["Workorder Code", "Control No.", "Type", "Status", "Actions"];
        $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'completed', 'cancelled')");
        $workorders = $query->paginate(5);
        return view('pages.workorders.index-workorders', compact('workorders', 'columns'));
    }

    public function getEditWorkorder($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $priorities = PriorityLevel::cases();

        return view('pages.workorders.edit-workorder', compact('workorder', 'priorities'));
    }

    public function updateWorkorder(WorkorderValidation $request, $id){
        $validated = $request->validated();
        $workorder = Workorder::findOrFail($id);

        $data = $validated;

        if(!$validated['has_vehicle']){
            $data = array_merge($data, [
                'has_minor' => false,
                'vehicle_minor_details' => null,
                'has_major' => false,
                'vehicle_major_details' => null,
                'has_change_oil' => false,
                'last_change_oil_date' => null,
                'meter_reading' => null,
                'has_insurance' => false,
                'insurance_date' => null,
                'has_registration' => false,
                'registration_date' => null,
                'has_other' => false,
                'other_details' => null,
            ]);
        }

        if(!$validated['is_subcontractor']){
            $data = array_merge($data, [
                'sub_name' => null,
                'sub_document' => null,
                'sub_details' => null,
                'sub_cost' => null,
                'sub_date_released' => null,
                'sub_date_returned' => null,
            ]);
        }

        if(!$validated['is_inhouse']){
            $data = array_merge($data, [
                'priority_level' => null,
                'inhouse_cost' => null,
                'estimated_duration' => null,
                'instructions' => null,
            ]);
        }

        //vehicle + spare parts false checker wipe
        $data['spare_parts'] = $validated['has_spare_parts'] ? ($validated['spare_parts'] ?? null) : null;
        $data['vehicle_minor_details'] = $validated['has_minor'] ? $validated['vehicle_minor_details'] : null;
        $data['vehicle_major_details'] = $validated['has_major'] ? $validated['vehicle_major_details'] : null;
        $data['last_change_oil_date'] = $validated['has_change_oil'] ? $validated['last_change_oil_date'] : null;
        $data['meter_reading'] = $validated['has_change_oil'] ? $validated['meter_reading'] : null;
        $data['insurance_date'] = $validated['has_insurance'] ? $validated['insurance_date'] : null;
        $data['registration_date'] = $validated['has_registration'] ? $validated['registration_date'] : null;
        $data['other_details'] = $validated['has_other'] ? $validated['other_details'] : null;

        $workorder->update($data);

        return redirect()->route("workorders.index")->with("success","Workorder edited successfully!");
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

                $asset = $workorder->request->asset;
                $asset->update([
                    'status' => AssetStatus::ACTIVE->value
                ]);
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
