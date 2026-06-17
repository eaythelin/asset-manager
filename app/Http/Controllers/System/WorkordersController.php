<?php

namespace App\Http\Controllers\System;

use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use App\Enums\AssetStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkorderValidation;
use Illuminate\Http\Request;
use App\Models\Workorder;
use App\Models\Employee;
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

        $columns = ["Workorder Code", "Control No.", "Type" ,"Start Date", "Date Finished", "Status", "Actions"];
        $query->orderByRaw("FIELD(status, 'pending', 'in_progress', 'completed', 'cancelled')");
        $workorders = $query->paginate(5);
        return view('pages.workorders.index-workorders', compact('workorders', 'columns'));
    }

    public function getEditWorkorder($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $priorities = PriorityLevel::cases();
        $employees = Employee::where('is_maintenance', true)->orderBy('name')->pluck('name','id');

        return view('pages.workorders.edit-workorder', compact('workorder', 'priorities', 'employees'));
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

        if($validated['type'] !== WorkorderType::SUBCONTRACTOR->value){
            $data = array_merge($data, [
                'sub_name' => null,
                'sub_document' => null,
                'sub_details' => null,
                'sub_cost' => null,
                'sub_date_released' => null,
                'sub_date_returned' => null,
            ]);
        }

        if($validated['type'] !== WorkorderType::IN_HOUSE->value){
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

        $workorder->update([
            "started_at" => now(),
            "status" => WorkorderStatus::IN_PROGRESS->value
        ]);

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
        $workorder = Workorder::findOrFail($id);

        if($workorder->is_subcontractor){
            if(!$workorder->sub_name || !$workorder->sub_document || !$workorder->sub_cost || !$workorder->sub_date_released || !$workorder->sub_date_returned){
                return redirect()->back()->with('error','Please fill in required Subcontractor fields before completing!');
            }
        }

        if($workorder->is_inhouse){
            if(!$workorder->priority_level || !$workorder->estimated_duration || !$workorder->inhouse_cost){
                return back()->with('error', 'Please fill in required In-House fields before completing!');
            }
        }

        if(!$workorder->is_inhouse && !$workorder->is_subcontractor){
            return back()->with('error', 'Please select In-House or Subcontractor before completing!');
        }

        if($workorder->has_spare_parts){
            if(empty($workorder->spare_parts)){
                return back()->with('error', 'Please add at least one spare part!');
            }
        }

        $workorder->update([
            'finished_at' => now(),
            'status' => WorkorderStatus::COMPLETED,
            'completed_by' => auth()->user()->id
        ]);

        $workorder->request->asset->update([
            'status' => AssetStatus::ACTIVE->value
        ]);

        return redirect()->route("workorders.index")->with('success', 'Workorder completed successfully!');
    }

    public function getWOPage($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $backRoute = 'workorders.index';
        $backLabel = 'Workorders';

        return view('pages.workorders.show-workorder', compact('workorder', 'backLabel', 'backRoute'));
    }
}
