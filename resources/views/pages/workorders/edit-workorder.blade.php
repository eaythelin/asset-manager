@extends('layouts.pageslayout')
@section('content')
<div class = "md:mx-4">

  <x-session-error />

  <div class="mb-4">
      <x-back-link route="workorders.index">Return to Workorders</x-back-link>
  </div>

  <x-validation-error />

  <form method="POST" action="{{ route('workorders.update', $workorder->id) }}">
    @csrf
    @method('PUT')
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4">
      <x-page-section-header title="Repair and Maintenance Request Form">
          <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
      </x-page-section-header>

      <div class = "flex flex-col sm:flex-row gap-6">
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="control_number">Control Number</x-page-label>
            <x-page-input value="{{ $workorder->request->control_number }}" name="control_number" id="control_number" disabled/>
          </div>

          <div class="form-row">
            <x-page-label for="requisitioner">Requisitioner</x-page-label>
            <x-page-input value="{{ $workorder->request->requestedBy->name }}" name="requisitioner" id="requisitioner" disabled/>
          </div>

          <div class="form-row">
            <x-page-label for="asset">Equipment/Vehicle</x-page-label>
            <x-page-input value="{{ $workorder->request->asset->name }}" name="asset" id="asset" disabled/>
          </div>

          <div class="form-row">
            <x-page-label for="description">Description/Plate No.</x-page-label>
            <x-page-textarea name="description" id="description" disabled>{{ old('description', $workorder->request->description) }}</x-page-textarea>
          </div>
        </div>

        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="department">Department</x-page-label>
            <x-page-input value="{{ $workorder->request->department->name }}" name="department" id="department" disabled/>
          </div>

          <div class="form-row">
            <x-page-label for="date">Date</x-page-label>
            <x-page-input value="{{  $workorder->request->created_at->format('M d, Y') }}" name="date" id="date" disabled/>
          </div>

          <div class="form-row">
            <x-page-label for="request_type">Request Type</x-page-label>
            <x-page-input value="{{ $workorder->request->request_type->label() }}" name="request_type" id="request_type" disabled/>
          </div>
        </div>
      </div>

      <hr class="border-gray-300 m-5">
      <div x-data="{ is_subcontractor: {{ old('is_subcontractor', $workorder->is_subcontractor) ? 'true' : 'false' }} }">
        <input type="checkbox" id="is_subcontractor" name="is_subcontractor" x-model="is_subcontractor" class="checkbox checkbox-primary checkbox-sm">
        <label for="is_subcontractor" class="font-medium ml-2 text-lg">Repair by Subcontractor</label>

        <template x-if="is_subcontractor">
          <div>
            <p class="font-medium text-gray-600 mb-2 mt-5">Subcontractor Details: </p>
            <div class = "flex flex-col sm:flex-row gap-6 mt-2">
              <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="sub_name" :required="true">Name/Company</x-page-label>
                  <x-page-input value="{{ old('sub_name', $workorder->sub_name) }}" name="sub_name" id="sub_name"/>
                </div>

                <div class="form-row">
                  <x-page-label for="sub_document" :required="true">Request Document</x-page-label>
                  <x-page-input value="{{ old('sub_document', $workorder->sub_document) }}" name="sub_document" id="sub_document"/>
                </div>

                <div class="form-row">
                  <x-page-label for="sub_details">Details of Activities</x-page-label>
                  <x-page-textarea name="sub_details" id="sub_details">{{ old('sub_details', $workorder->sub_details) }}</x-page-textarea>
                </div>
              </div>

              <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="sub_cost" :required="true">Cost</x-page-label>
                  <x-page-input value="{{ old('sub_cost', $workorder->sub_cost) }}" name="sub_cost" id="sub_cost" type="number"/>
                </div>

                <div class="form-row">
                  <x-page-label for="sub_date_released" :required="true">Date Released</x-page-label>
                  <x-page-input value="{{ old('sub_date_released',  $workorder->sub_date_released?->format('Y-m-d')) }}" name="sub_date_released" id="sub_date_released" type="date"/>
                </div>

                <div class="form-row">
                  <x-page-label for="date_returned" :required="true">Date Returned</x-page-label>
                  <x-page-input value="{{ old('date_returned', $workorder->sub_date_returned?->format('Y-m-d')) }}" name="sub_date_returned" id="sub_date_returned" type="date"/>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>

      <hr class="border-gray-300 m-5">
      <div x-data="{ is_inhouse: {{ old('is_inhouse', $workorder->is_inhouse) ? 'true' : 'false' }} }">
        <input type="checkbox" id="is_inhouse" name="is_inhouse" x-model="is_inhouse" class="checkbox checkbox-primary checkbox-sm">
        <label for="is_inhouse" class="font-medium ml-2 text-lg">In House Maintenance</label>

        <template x-if="is_inhouse">
          <div class = "flex flex-col sm:flex-row gap-6 mt-5">
            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="employees" :required="true">Assigned Maintenance Crew(s)</x-page-label>
                <x-page-input value="{{ old('employees', $workorder->employees) }}"/>
              </div>

              <div class="form-row">
                <x-page-label for="priority_level" :required="true">Priority Level</x-page-label>
                <x-page-select name="priority_level" id="priority_level">
                  <option value="" disabled selected>--Select Priority Level--</option>
                  @foreach($priorities as $priority)
                    <option value="{{ $priority->value }}" {{ old('priority_level', $workorder->priority_level?->value) == $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
                  @endforeach
                </x-page-select>
              </div>

              <div class="form-row">
                <x-page-label for="instructions">Instructions</x-page-label>
                <x-page-textarea name="instructions" id="instructions">{{ old('instructions', $workorder->instructions) }}</x-page-textarea>
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="estimated_duration" :required="true">Estimated Day(s) Hour(s)</x-page-label>
                <x-page-input value="{{ old('estimated_duration', $workorder->estimated_duration) }}" name="estimated_duration" id="estimated_duration"/>
              </div>

              <div class="form-row">
                <x-page-label for="inhouse_cost" :required="true">Cost</x-page-label>
                <x-page-input value="{{ old('inhouse_cost', $workorder->inhouse_cost) }}" name="inhouse_cost" id="inhouse_cost" type="number"/>
              </div>
            </div>
          </div>
        </template>
      </div>

      <hr class="border-gray-300 m-5">
      <div x-data="{ has_spare_parts: {{ old('has_spare_parts', $workorder->has_spare_parts) ? 'true' : 'false' }} }">
        <input type="checkbox" id="has_spare_parts" name="has_spare_parts" x-model="has_spare_parts" class="checkbox checkbox-primary checkbox-sm">
        <label for="has_spare_parts" class="font-medium ml-2 text-lg">Requested Spare Parts</label>

        <template x-if="has_spare_parts">
          <div x-data="{ rows: {{ json_encode(old('spare_parts', $workorder->spare_parts ?? [])) }} }" class="overflow-x-auto mt-5">
            <table class="table">
              <thead>
                <tr>
                  <th>Parts/Supplies</th>
                  <th>Description & Specification</th>
                  <th>Quantity</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <template x-for="(row, index) in rows" :key="index">
                  <tr class="hover:bg-base-300">
                    <td><input type="text" x-model="row.part" :name="`spare_parts[${index}][part]`" class="input input-sm w-full"></td>
                    <td><input type="text" x-model="row.description" :name="`spare_parts[${index}][description]`" class="input input-sm w-full"></td>
                    <td><input type="number" x-model="row.quantity" :name="`spare_parts[${index}][quantity]`" class="input input-sm w-full"></td>
                    <td><button type="button" @click="rows.splice(index, 1)" class="btn btn-error btn-sm text-white">Remove</button></td>
                  </tr>
                </template>
              </tbody>
            </table>
            <button type="button" @click="rows.push({part: '', description: '', quantity: 1})" class="btn btn-primary btn-sm mt-2 ml-2">
              + Add Row
            </button>
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
            <x-page-input name="started_at" id="started_at" value="{{ $workorder->started_at?->format('Y-m-d\TH:i') }}" disabled/>
          </div>
        </div>

        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="finished_at">Date/hour Finished</x-page-label>
            <x-page-input name="finished_at" id="finished_at" value="{{ $workorder->finished_at?->format('Y-m-d\TH:i') }}" disabled/>
          </div>
        </div>
      </div>

      <div class="form-row mt-5">
        <x-page-label for="accomplishment_details">Details (if nay)</x-page-label>
        <textarea name="accomplishment_details" id="accomplishment_details" class="textarea w-full md:mx-10 border-2 border-gray-400">{{ old('accomplishment_details', $workorder->accomplishment_details) }}</textarea>
      </div>

      <hr class="border-gray-300 m-5">
      <div x-data="{ has_vehicle: {{ old('has_vehicle', $workorder->has_vehicle) ? 'true' : 'false' }} }">
        <input type="checkbox" id="has_vehicle" name="has_vehicle" x-model="has_vehicle" class="checkbox checkbox-primary checkbox-sm">
        <label for="has_vehicle" class="font-medium ml-2 text-lg">Vehicle Repairs and Maintenance</label>

        <template x-if="has_vehicle">
          <div class = "flex flex-col sm:flex-row gap-6 mt-5">
            <div class = "flex flex-col flex-1 gap-4" x-data="{has_change_oil : {{ old('has_change_oil', $workorder->has_change_oil) ? 'true' : 'false' }} }">
              <div class="form-row" x-data="{has_minor: {{ old('has_minor', $workorder->has_minor) ? 'true' : 'false' }} }">
                <input type="checkbox" x-model="has_minor" name="has_minor" class="checkbox checkbox-sm checkbox-primary">
                <x-page-label for="vehicle_minor_details">Minor</x-page-label>
                <input :disabled="!has_minor" name="vehicle_minor_details" :class="!has_minor ? 'opacity-50' : ''"
                class="input max-w-xs border-2 border-gray-400 rounded-lg" id="vehicle_minor_details"
                value="{{ old('vehicle_minor_details', $workorder->vehicle_minor_details) }}">
              </div>

              <div class="form-row" x-data="{has_major: {{ old('has_major', $workorder->has_major) ? 'true' : 'false' }} }">
                <input type="checkbox" x-model="has_major" name="has_major" class="checkbox checkbox-sm checkbox-primary">
                <x-page-label for="vehicle_major_details">Major</x-page-label>
                <input :disabled="!has_major" name="vehicle_major_details" :class="!has_major ? 'opacity-50' : ''"
                class="input max-w-xs border-2 border-gray-400 rounded-lg" id="vehicle_major_details"
                value="{{ old('vehicle_major_details', $workorder->vehicle_major_details) }}">
              </div>

              <div class="form-row">
                <input type="checkbox" x-model="has_change_oil" name="has_change_oil" id="has_change_oil" class="checkbox checkbox-sm checkbox-primary">
                <x-page-label for="has_change_oil">Change Oil</x-page-label>
              </div>

              <div class="form-row md:ml-8">
                <x-page-label for="last_change_oil_date">Last Change Oil Date</x-page-label>
                <input type="date" :disabled="!has_change_oil" name="last_change_oil_date" :class="!has_change_oil ? 'opacity-50' : ''"
                class="input max-w-xs border-2 border-gray-400 rounded-lg" id="last_change_oil_date"
                value="{{ old('last_change_oil_date', $workorder->last_change_oil_date?->format('Y-m-d')) }}">
              </div>

              <div class="form-row md:ml-8">
                <x-page-label for="meter_reading">Meter Reading</x-page-label>
                <input :disabled="!has_change_oil" name="meter_reading" :class="!has_change_oil ? 'opacity-50' : ''"
                  class="input max-w-xs border-2 border-gray-400 rounded-lg" id="meter_reading"
                  value="{{ old('meter_reading', $workorder->meter_reading) }}">
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row" x-data="{ has_insurance: {{ old('has_insurance', $workorder->has_insurance) ? 'true' : 'false' }} }">
                  <input type="checkbox" x-model="has_insurance" name="has_insurance" class="checkbox checkbox-sm checkbox-primary">
                  <x-page-label for="insurance_date">Insurance</x-page-label>
                  <span class="text-sm text-gray-500">Expiry Date:</span>
                  <input type="date" :disabled="!has_insurance" name="insurance_date"
                    :class="!has_insurance ? 'opacity-50' : ''"
                    class="input border-2 border-gray-400 rounded-lg" id="insurance_date"
                    value="{{ old('insurance_date', $workorder->insurance_date?->format('Y-m-d')) }}">
              </div>

              <div class="form-row" x-data="{ has_registration: {{ old('has_registration', $workorder->has_registration) ? 'true' : 'false' }} }">
                  <input type="checkbox" x-model="has_registration" name="has_registration" class="checkbox checkbox-sm checkbox-primary">
                  <x-page-label for="registration_date">Registration</x-page-label>
                  <span class="text-sm text-gray-500">Expiry Date:</span>
                  <input type="date" :disabled="!has_registration" name="registration_date"
                    :class="!has_registration ? 'opacity-50' : ''"
                    class="input border-2 border-gray-400 rounded-lg" id="registration_date"
                    value="{{ old('registration_date', $workorder->registration_date?->format('Y-m-d')) }}">
              </div>

              <div class="form-row" x-data="{has_other: {{ old('has_other', $workorder->has_other) ? 'true' : 'false' }} }">
                <input type="checkbox" x-model="has_other" name="has_other" class="checkbox checkbox-sm checkbox-primary">
                <x-page-label for="other_details">Other</x-page-label>
                <textarea :disabled="!has_other" name="other_details" id="other_details" :class="!has_other ? 'opacity-50' : ''" class="textarea max-w-xs border-2 border-gray-400">{{ old('other_details', $workorder->other_details) }}</textarea>
              </div>
            </div>
          </div>
        </template>
      </div>
      <x-page-edit-submit-button />

    </div>
  </form>
</div>
@endsection
