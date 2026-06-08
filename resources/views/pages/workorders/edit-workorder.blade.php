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
                  <x-page-input value="{{ old('sub_document', $workorder->sub_document) }}" name="sub_name" id="sub_name"/>
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
                  <x-page-input value="{{ old('sub_date_released', $workorder->sub_date_released) }}" name="sub_date_released" id="sub_date_released" type="date"/>
                </div>

                <div class="form-row">
                  <x-page-label for="sub_date_returned" :required="true">Date Returned</x-page-label>
                  <x-page-input value="{{ old('sub_date_returned', $workorder->sub_date_returned) }}" name="sub_name" id="sub_name" type="date"/>
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
                <x-page-input value="{{ old('employees', $workorder->employees) }}" name="employees" id="employees"/>
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

      <x-page-edit-submit-button />

    </div>
  </form>
</div>
@endsection
