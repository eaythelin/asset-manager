<?php

namespace App\Http\Controllers\System;

use App\Enums\PriorityLevel;
use App\Enums\WorkorderStatus;
use App\Enums\WorkorderType;
use App\Enums\AssetStatus;
use App\Enums\RequestTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkorderValidation;
use Illuminate\Http\Request;
use App\Models\Workorder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\ActivityLog;
use App\Enums\ActivityAction;
use App\Enums\ActivityModule;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as PdfWriter;

class WorkordersController extends Controller
{
    public function getWorkOrders(Request $request){
        $query = Workorder::with('request');

        if(auth()->user()->hasRole('Maintenance Crew')){
            $query->whereHas('assignedMaintenanceCrew', function($q) {
                $q->where('users.id', auth()->id());
            });
        }

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
        $requestTypes = RequestTypes::cases();
        $maintenanceCrew = User::role('Maintenance Crew')->pluck('name', 'id');

        return view('pages.workorders.edit-workorder', compact('workorder', 'priorities', 'maintenanceCrew','requestTypes'));
    }

    public function updateWorkorder(WorkorderValidation $request, $id){
        $validated = $request->validated();
        $workorder = Workorder::findOrFail($id);

        try {
            DB::transaction(function() use($validated, $workorder){
                $data = $validated;
                unset($data['employee_1'], $data['employee_2']);

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
                $data['spare_parts'] = $validated['has_spare_parts']
                    ? collect($validated['spare_parts'] ?? [])
                        ->filter(fn ($row) => !empty($row['part']) && !empty($row['quantity']))
                        ->values()
                        ->toArray()
                    : null;
                $data['vehicle_minor_details'] = $validated['has_minor'] ? $validated['vehicle_minor_details'] : null;
                $data['vehicle_major_details'] = $validated['has_major'] ? $validated['vehicle_major_details'] : null;
                $data['last_change_oil_date'] = $validated['has_change_oil'] ? $validated['last_change_oil_date'] : null;
                $data['meter_reading'] = $validated['has_change_oil'] ? $validated['meter_reading'] : null;
                $data['insurance_date'] = $validated['has_insurance'] ? $validated['insurance_date'] : null;
                $data['registration_date'] = $validated['has_registration'] ? $validated['registration_date'] : null;
                $data['other_details'] = $validated['has_other'] ? $validated['other_details'] : null;

                $workorder->update($data);

                $employees = array_filter([
                    $validated['employee_1'] ?? null,
                    $validated['employee_2'] ?? null
                ]);

                $workorder->assignedMaintenanceCrew()->sync($employees);

                ActivityLog::log(ActivityModule::WORKORDER, ActivityAction::UPDATED, "Updated workorder: " . $workorder->request->control_number);
            });
        }catch(Exception $e){
            dd($e->getMessage());
            return redirect()->route("workorders.edit", $workorder->id)->with('error', 'Something went wrong!');
        }


        return redirect()->route("workorders.index")->with("success","Workorder edited successfully!");
    }

    public function startWO($id){
        $workorder = Workorder::findOrFail($id);

        $workorder->update([
            "started_at" => now(),
            "status" => WorkorderStatus::IN_PROGRESS->value
        ]);

        ActivityLog::log(ActivityModule::WORKORDER, ActivityAction::STARTED, "Started workorder: " . $workorder->request->control_number);

        return back()->with('success','Workorder status changed successfully!');
    }

    public function cancelWO($id){
      $workorder = Workorder::findOrFail($id);
        try{
            DB::transaction(function () use ($workorder){
                $workorder->update([
                    'status' => WorkorderStatus::CANCELLED
                ]);

                $asset = $workorder->request->asset;
                $asset->update([
                    'status' => AssetStatus::ACTIVE->value
                ]);
            });

            ActivityLog::log(ActivityModule::WORKORDER, ActivityAction::CANCELLED, "Cancelled workorder: " . $workorder->request->control_number);

            return redirect()->route("workorders.index")->with('success', 'Workorder cancelled successfully!');

        }catch (Exception $e){
            return redirect()->route("workorders.index")->with('error', 'Something went wrong!');
        }
    }

    public function completeWO($id){
        $workorder = Workorder::findOrFail($id);

        if($workorder->type !== WorkorderType::IN_HOUSE && $workorder->type !== WorkorderType::SUBCONTRACTOR){
            return back()->with('error', 'Please select In-House or Subcontractor before completing!');
        }

        if($workorder->type === WorkorderType::SUBCONTRACTOR){
            if(!$workorder->sub_name || !$workorder->sub_document || !$workorder->sub_cost || !$workorder->sub_date_released || !$workorder->sub_date_returned){
                return redirect()->back()->with('error','Please fill in required Subcontractor fields before completing!');
            }
        }

        if($workorder->type === WorkorderType::IN_HOUSE){
            if(!$workorder->priority_level || !$workorder->estimated_duration || !$workorder->inhouse_cost){
                return back()->with('error', 'Please fill in required In-House fields before completing!');
            }

            if($workorder->assignedEmployees->isEmpty()){
                return back()->with('error', 'Please assigned at least one maintenance crew before completing!');
            }
        }

        if($workorder->has_spare_parts){
            if(empty($workorder->spare_parts)){
                return back()->with('error', 'Please add at least one spare part!');
            }
        }

        try {
            DB::transaction(function() use($workorder){
                $workorder->update([
                    'finished_at' => now(),
                    'status' => WorkorderStatus::COMPLETED,
                    'completed_by' => auth()->user()->id
                ]);

                $workorder->request->asset->update([
                    'status' => AssetStatus::ACTIVE->value
                ]);

                ActivityLog::log(ActivityModule::WORKORDER, ActivityAction::COMPLETED, "Completed workorder: " . $workorder->request->control_number);
            });

            return redirect()->route("workorders.index")->with('success', 'Workorder completed successfully!');

        }catch (Exception $e){
            return redirect()->route("workorders.index")->with('error', 'Something went wrong!');
        }
    }

    public function getWOPage($id){
        $workorder = Workorder::with('request')->findOrFail($id);
        $backRoute = 'workorders.index';
        $backLabel = 'Work Orders';
        $requestTypes = RequestTypes::cases();

        return view('pages.workorders.show-workorder', compact('workorder', 'backLabel', 'backRoute', 'requestTypes'));
    }

    public function downloadPDF($id){
        $workorder = Workorder::findOrFail($id);

        $templatePath = storage_path('app/templates/rmrf_template_2.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        //inserting data (Request Head)
        $sheet->setCellValue('K4', $workorder->request->control_number);
        $sheet->setCellValue('C6', $workorder->request->requestedBy->name);
        $sheet->setCellValue('G6', $workorder->request->department->name);
        $sheet->setCellValue('K6', $workorder->request->created_at->format('M d, Y'));
        $sheet->setCellValue('C7', $workorder->request->asset->name);
        $sheet->setCellValue('D8', $workorder->request->description);
        $sheet->setCellValue('I11', $workorder->request->approvedBy->name);

        //shrink to fit
        $sheet->getStyle('K4')->getAlignment()->setShrinkToFit(true);
        $sheet->getStyle('C6')->getAlignment()->setShrinkToFit(true);
        $sheet->getStyle('C7')->getAlignment()->setShrinkToFit(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'RMRF_Form.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
