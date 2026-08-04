@extends('layouts.pageslayout')
@section('content')

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link :route="$backRoute" :params="$workorder->request->asset->id">Return to {{ $backLabel }}</x-back-link>
  </div>

	<div class="bg-white p-4 rounded-2xl shadow-2xl mt-4">
    <x-page-section-header title="Repair and Maintenance Request Form">
        <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
    </x-page-section-header>

    <div class = "flex flex-col sm:flex-row gap-6">
      <div class = "flex flex-col flex-1 gap-4">
        <div class="form-row">
          <x-page-label for="requisitioner">Requisitioner</x-page-label>
          <x-page-input value="{{ $workorder->request->requestedBy->name }}" name="requisitioner" id="requisitioner" readonly/>
        </div>

        <div class="form-row">
          <x-page-label for="asset">Equipment/Vehicle</x-page-label>
          <x-page-input value="{{ $workorder->request->asset->name }}" name="asset" id="asset" readonly/>
        </div>

        <div class="form-row">
          <x-page-label for="description">Description/Plate No.</x-page-label>
          <x-page-textarea name="description" id="description" readonly>{{ old('description', $workorder->request->description) }}</x-page-textarea>
        </div>
      </div>

      <div class = "flex flex-col flex-1 gap-4">
        <div class="form-row">
          <x-page-label for="control_number">Control Number</x-page-label>
          <x-page-input value="{{ $workorder->request->control_number }}" name="control_number" id="control_number" readonly/>
        </div>
        <div class="form-row">
          <x-page-label for="department">Department</x-page-label>
          <x-page-input value="{{ $workorder->request->department->name }}" name="department" id="department" readonly/>
        </div>

        <div class="form-row">
          <x-page-label for="date">Date</x-page-label>
          <x-page-input value="{{  $workorder->request->created_at->format('M d, Y') }}" name="date" id="date" readonly/>
        </div>

        <div class="form-row">
          <x-page-label for="request_type">Type of Request</x-page-label>
          <div class="flex gap-6">
            @foreach ($requestTypes as $type)
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="request_type" value="{{ $type->value }}"
                  {{ old('request_type', $workorder->request->request_type->value) == $type->value ? 'checked' : '' }}
                  class="checkbox rounded-none checkbox-sm checkbox-primary" disabled>
                {{ $type->label() }}
              </label>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-6 mt-10">
      <div class="form-row flex-1">
        <x-page-label for="requested_by">Requested By</x-page-label>
        <x-page-input value="{{ $workorder->request->requestedBy->name }}" name="requested_by" id="requested_by" readonly/>
      </div>

      <div class="form-row flex-1">
        <x-page-label for="approved_by">Request Approved By</x-page-label>
        <x-page-input value="{{ $workorder->request->approvedBy?->name }}" name="approved_by" id="approved_by" readonly/>
      </div>
    </div>

    <hr class="border-gray-300 m-5">
    <div x-data="{ workorder_type: '{{ $workorder->type }}'}">
      <input type="radio" name="type" value="subcontractor" x-model="workorder_type" class="checkbox checkbox-primary rounded-none checkbox-sm">
      <label class="font-medium ml-2 text-lg">Repair by Subcontractor</label>

      <template x-if="workorder_type === 'subcontractor'">
        <div>
          <p class="font-medium text-gray-600 mb-2 mt-5">Subcontractor Details: </p>
          <div class = "flex flex-col sm:flex-row gap-6 mt-2">
            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="sub_name" :required="true">Name/Company</x-page-label>
                <x-page-input value="{{ $workorder->sub_name }}" name="sub_name" id="sub_name" readonly />
              </div>

              <div class="form-row">
                <x-page-label for="sub_document" :required="true">Request Document</x-page-label>
                <x-page-input value="{{ $workorder->sub_document }}" name="sub_document" id="sub_document" readonly/>
              </div>

              <div class="form-row">
                <x-page-label for="sub_details">Details of Activities</x-page-label>
                <x-page-textarea name="sub_details" id="sub_details" readonly>{{ $workorder->sub_details }}</x-page-textarea>
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="sub_cost" :required="true">Cost</x-page-label>
                <x-page-input value="{{ $workorder->sub_cost }}" name="sub_cost" id="sub_cost" type="number" readonly/>
              </div>

              <div class="form-row">
                <x-page-label for="sub_date_released" :required="true">Date Released</x-page-label>
                <x-page-input value="{{ $workorder->sub_date_released?->format('Y-m-d') }}" name="sub_date_released" id="sub_date_released" type="date" readonly/>
              </div>

              <div class="form-row">
                <x-page-label for="date_returned" :required="true">Date Returned</x-page-label>
                <x-page-input value="{{ $workorder->sub_date_returned?->format('Y-m-d') }}" name="sub_date_returned" id="sub_date_returned" type="date" readonly/>
              </div>
            </div>
          </div>
        </div>
      </template>

      <hr class="border-gray-300 m-5">
      <input type="radio" name="type" value="in_house" x-model="workorder_type" class="checkbox checkbox-primary checkbox-sm rounded-none">
      <label class="font-medium ml-2 text-lg">In House Maintenance</label>

      <template x-if="workorder_type === 'in_house'">
        <div>
          <div class="form-row items-start mt-2">
            <x-page-label for="employee_1">Assigned Maintenance Crew(s)</x-page-label>
            <div class="flex flex-col md:flex-row gap-4 md:gap-10">
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">Crew 1<span><span class="text-base text-red-600 tooltip tooltip-right" data-tip="Required">*</span></span></span>
                <input name="employee_1" value="{{ $workorder->assignedMaintenanceCrew->get(0)?->name }}" class="select border-2 border-gray-400 rounded-lg w-80" readonly/>
              </div>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1 sm:mt-2">Crew 2 (Optional)</span>
                <input name="employee_1" value="{{ $workorder->assignedMaintenanceCrew->get(1)?->name }}" class="select border-2 border-gray-400 rounded-lg w-80" readonly/>
              </div>
            </div>
          </div>
          <div class = "flex flex-col sm:flex-row gap-6 mt-5">
            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="priority_level" :required="true">Priority Level</x-page-label>
                <x-page-input value="{{ $workorder->priority_level?->label() }}" readonly/>
              </div>

              <div class="form-row">
                <x-page-label for="instructions">Instructions</x-page-label>
                <x-page-textarea name="instructions" id="instructions" readonly>{{ $workorder->instructions }}</x-page-textarea>
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="estimated_duration" :required="true">Estimated Day(s) Hour(s)</x-page-label>
                <x-page-input value="{{ $workorder->estimated_duration }}" name="estimated_duration" id="estimated_duration" readonly/>
              </div>

              <div class="form-row">
                <x-page-label for="inhouse_cost" :required="true">Cost</x-page-label>
                <x-page-input value="{{ $workorder->inhouse_cost }}" name="inhouse_cost" id="inhouse_cost" type="number" readonly/>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>

    <hr class="border-gray-300 m-5">
    <div x-data="{ has_spare_parts: {{ $workorder->has_spare_parts ? 'true' : 'false' }} }">
      <input type="checkbox" id="has_spare_parts" name="has_spare_parts" x-model="has_spare_parts" class="checkbox checkbox-primary checkbox-sm rounded-none">
      <label for="has_spare_parts" class="font-medium ml-2 text-lg">Requested Spare Parts</label>

      <template x-if="has_spare_parts">
        <div x-data="{ rows: {{ json_encode(array_pad($workorder->spare_parts ?? [], 5, ['part' => '', 'description' => '', 'quantity' => ''])) }} }" class="overflow-x-auto mt-5">
          <table class="table">
            <thead>
              <tr>
                <th>Parts/Supplies</th>
                <th>Description & Specification <span class="text-xs">(Brand names,size,code number, etc.)</span></th>
                <th>Quantity</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(row, index) in rows" :key="index">
                <tr class="hover:bg-base-300">
                  <td><input type="text" x-model="row.part" :name="`spare_parts[${index}][part]`" class="input input-sm w-full" readonly></td>
                  <td><input type="text" x-model="row.description" :name="`spare_parts[${index}][description]`" class="input input-sm w-full" readonly></td>
                  <td><input type="number" x-model="row.quantity" :name="`spare_parts[${index}][quantity]`" class="input input-sm w-full" readonly></td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </template>
    </div>

    <x-page-section-header title="Accomplisment Report" :breakline="true">
      <x-heroicon-s-clipboard-document-check class="size-6 text-blue-700"/>
    </x-page-section-header>

    <div class = "flex flex-col sm:flex-row gap-6">
      <div class = "flex flex-col flex-1 gap-4">
        <div class="form-row">
          <x-page-label for="started_at">Date/hour Started</x-page-label>
          <x-page-input name="started_at" id="started_at" value="{{ $workorder->started_at?->format('M d, Y h:i A') }}" readonly/>
        </div>
      </div>

      <div class = "flex flex-col flex-1 gap-4">
        <div class="form-row">
          <x-page-label for="finished_at">Date/hour Finished</x-page-label>
          <x-page-input name="finished_at" id="finished_at" value="{{ $workorder->finished_at?->format('M d, Y h:i A') }}" readonly/>
        </div>
      </div>
    </div>

    <div class="form-row mt-5">
      <x-page-label for="accomplishment_details">Details (if any)</x-page-label>
      <textarea name="accomplishment_details" id="accomplishment_details" class="textarea w-full md:mx-10 border-2 border-gray-400" readonly>{{ $workorder->accomplishment_details }}</textarea>
    </div>

    <div class="flex flex-col sm:flex-row gap-6 mt-5">
      <div class="form-row flex-1">
        <x-page-label for="inspected_by">Inspected By</x-page-label>
        <x-page-input name="inspected_by" id="inspected_by" value="{{ $workorder->completedBy?->name }}" readonly/>
      </div>

      <div class="form-row flex-1">
        <x-page-label for="accepted_by">Accepted By</x-page-label>
        <x-page-input name="accepted_by" id="accepted_by" value="{{ $workorder->status->value === 'completed' ? $workorder->request?->requestedBy?->name : '' }}" readonly/>
      </div>
    </div>

    <hr class="border-gray-300 m-5">
    <div x-data="{ has_vehicle: {{ $workorder->has_vehicle ? 'true' : 'false' }} }">
      <input type="checkbox" id="has_vehicle" name="has_vehicle" x-model="has_vehicle" class="checkbox checkbox-primary checkbox-sm rounded-none">
      <label for="has_vehicle" class="font-medium ml-2 text-lg">Vehicle Repairs and Maintenance</label>

      <template x-if="has_vehicle">
        <div class = "flex flex-col sm:flex-row gap-6 mt-5">
          <div class = "flex flex-col flex-1 gap-4" x-data="{{ $workorder->has_change_oil ? 'true' : 'false'  }} }">
            <div class="form-row" x-data="{has_minor: {{ $workorder->has_minor ? 'true' : 'false' }} }">
              <input type="checkbox" x-model="has_minor" name="has_minor" class="checkbox checkbox-sm checkbox-primary rounded-none" disabled>
              <x-page-label for="vehicle_minor_details">Minor</x-page-label>
              <input :disabled="!has_minor" name="vehicle_minor_details" :class="!has_minor ? 'opacity-50' : ''"
              class="input max-w-xs border-2 border-gray-400 rounded-lg" id="vehicle_minor_details"
              value="{{ $workorder->vehicle_minor_details }}" readonly>
            </div>

            <div class="form-row" x-data="{has_major: {{ $workorder->has_major ? 'true' : 'false' }} }">
              <input type="checkbox" x-model="has_major" name="has_major" class="checkbox checkbox-sm checkbox-primary rounded-none" disabled>
              <x-page-label for="vehicle_major_details">Major</x-page-label>
              <input :disabled="!has_major" name="vehicle_major_details" :class="!has_major ? 'opacity-50' : ''"
              class="input max-w-xs border-2 border-gray-400 rounded-lg" id="vehicle_major_details"
              value="{{ $workorder->vehicle_major_details }}" readonly>
            </div>

            <div class="form-row">
              <input type="checkbox" x-model="has_change_oil" name="has_change_oil" id="has_change_oil" class="checkbox checkbox-sm checkbox-primary rounded-none" disabled>
              <x-page-label for="has_change_oil">Change Oil</x-page-label>
            </div>

            <div class="form-row md:ml-8">
              <x-page-label for="last_change_oil_date">Last Change Oil Date</x-page-label>
              <input type="date" :disabled="!has_change_oil" name="last_change_oil_date" :class="!has_change_oil ? 'opacity-50' : ''"
              class="input max-w-xs border-2 border-gray-400 rounded-lg" id="last_change_oil_date"
              value="{{ $workorder->last_change_oil_date?->format('Y-m-d') }}" readonly>
            </div>

            <div class="form-row md:ml-8">
              <x-page-label for="meter_reading">Meter Reading</x-page-label>
              <input :disabled="!has_change_oil" name="meter_reading" :class="!has_change_oil ? 'opacity-50' : ''"
                class="input max-w-xs border-2 border-gray-400 rounded-lg" id="meter_reading"
                value="{{ $workorder->meter_reading }}" readonly>
            </div>
          </div>

          <div class = "flex flex-col flex-1 gap-4">
            <div class="form-row" x-data="{ has_insurance: {{ $workorder->has_insurance ? 'true' : 'false' }} }">
                <input type="checkbox" x-model="has_insurance" name="has_insurance" class="checkbox checkbox-sm checkbox-primary rounded-none" disabled>
                <x-page-label for="insurance_date">Insurance</x-page-label>
                <span class="text-sm text-gray-500">Expiry Date:</span>
                <input type="date" :disabled="!has_insurance" name="insurance_date"
                  :class="!has_insurance ? 'opacity-50' : ''"
                  class="input border-2 border-gray-400 rounded-lg" id="insurance_date"
                  value="{{ $workorder->insurance_date?->format('Y-m-d') }}" readonly>
            </div>

            <div class="form-row" x-data="{ has_registration: {{ $workorder->has_registration ? 'true' : 'false' }} }">
                <input type="checkbox" x-model="has_registration" name="has_registration" class="checkbox checkbox-sm checkbox-primary rounded-none" disabled>
                <x-page-label for="registration_date">Registration</x-page-label>
                <span class="text-sm text-gray-500">Expiry Date:</span>
                <input type="date" :disabled="!has_registration" name="registration_date"
                  :class="!has_registration ? 'opacity-50' : ''"
                  class="input border-2 border-gray-400 rounded-lg" id="registration_date"
                  value="{{ $workorder->registration_date?->format('Y-m-d') }}" readonly>
            </div>

            <div class="form-row" x-data="{has_other: {{ $workorder->has_other ? 'true' : 'false' }} }">
              <input type="checkbox" x-model="has_other" name="has_other" class="checkbox checkbox-sm checkbox-primary rounded-none" disabled>
              <x-page-label for="other_details">Other</x-page-label>
              <textarea :disabled="!has_other" name="other_details" id="other_details" :class="!has_other ? 'opacity-50' : ''" class="textarea max-w-xs border-2 border-gray-400" readonly>{{ $workorder->other_details }}</textarea>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</div>
@endsection
