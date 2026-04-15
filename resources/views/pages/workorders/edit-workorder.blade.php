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
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4" x-data='{"woType": "{{ old('workorder_type', $workorder->workorder_type->value) }}"}'>
      <x-page-section-header title="General Information">
          <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
      </x-page-section-header>

      <div class = "flex flex-col sm:flex-row gap-6">
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="workorder_code" :required="true">Workorder Code</x-page-label>
            <x-page-input id="workorder_code" value="{{ $workorder->workorder_code }}" readonly/>
          </div>

          <div class="form-row">
            <x-page-label for="workorder_type" :required="true">Workorder Type</x-page-label>
            <x-page-input id="workorder_type" value="{{ $workorder->workorder_type->label() }}" readonly/>
            <input name="workorder_type" value="{{ $workorder->workorder_type->value }}" type="hidden">
          </div>

          <div class="form-row">
            <x-page-label for="priority_level">Priority</x-page-label>
            <x-page-select name="priority_level" id="priority_level">
              <option value="" disabled>--Select Priority Level--</option>
              @foreach($priorities as $priority)
                <option value="{{ $priority->value }}" {{ old('priority_level', $workorder->priority_level->value) == $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
              @endforeach
            </x-page-select>
          </div>
        </div>

          <div class = "flex flex-col flex-1 gap-4" x-data="{ woStatus: '{{ $workorder->status->value }}' }">
            <div class="form-row">
              <x-page-label for="start_date" :required="true">Start Date</x-page-label>
              <x-page-date-input name="start_date" id="start_date" value="{{ old('start_date', $workorder->start_date?->format('Y-m-d')) }}" 
                x-bind:readonly="['in_progress', 'overdue', 'completed'].includes(woStatus)"/>
            </div>

            <div class="form-row">
              <x-page-label for="end_date" :required="true">End Date</x-page-label>
              <x-page-date-input name="end_date" id="end_date" value="{{ old('end_date', $workorder->end_date?->format('Y-m-d')) }}" 
                x-bind:readonly="['in_progress', 'overdue', 'completed'].includes(woStatus)"/>
            </div>
          </div>
      </div>

      <template x-if="woType === 'requisition'">
        <div x-data="{ isNewAsset: {{ old('is_new_asset', $workorder->request->is_new_asset ?? false) ? 'true' : 'false' }} }">
          <x-page-section-header title="Requisition Details" :breakline="true">
            <x-heroicon-s-archive-box  class="size-6 text-green-700"/>    
          </x-page-section-header>

          <input type="hidden" name="is_new_asset" value="{{ $workorder->request?->is_new_asset }}">
          <input type="hidden" name="sub_wo_id" value="{{ $workorder->requisitionWorkorder?->id }}">
          <input type="hidden" name="status" value="{{ $workorder->status->value }}">
          
          <template x-if="isNewAsset">
            <div class = "flex flex-col sm:flex-row gap-6">
              <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="asset_name" :required="true">Asset Name</x-page-label>
                  <x-page-input name="asset_name" id="asset_name" value="{{ $workorder->requisitionWorkorder?->asset_name }}"/>
                </div>

                <div class="form-row">
                  <x-page-label for="category_id" :required="true">Category</x-page-label>
                  <x-page-input name="category_id" id="category_id" value="{{ $workorder->request?->category?->name }}" readonly/>
                </div>

                <div class="form-row">
                  <x-page-label for="sub_category_id">Subcategory</x-page-label>
                  <x-page-input name="sub_category_id" id="sub_category_id" value="{{ $workorder->request?->subCategory?->name }}" readonly/>
                </div>

                <x-workorders.quantity-field :value="$workorder->request->quantity"/>
                
              </div>

              <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="acquisition_date" :required="$workorder->status->value === 'in_progress'">Acquisition Date</x-page-label>
                  <x-page-date-input name="acquisition_date" id="acquisition_date" value="{{ old('acquisition_date', $workorder->requisitionWorkorder?->acquisition_date?->format('Y-m-d') ?? '') }}"/>
                </div>

                <div class="form-row">
                  <x-page-label for="estimated_cost" :required="$workorder->status->value === 'in_progress'">Estimated Cost</x-page-label>
                  <x-page-input name="estimated_cost" id="estimated_cost" value="{{ old('estimated_cost', $workorder->requisitionWorkorder?->estimated_cost ?? '') }}"/>
                </div>

                <x-workorders.supplier-field :suppliers="$suppliers" :workorder="$workorder->requisitionWorkorder?->supplier_id"/>

                <x-workorders.description-field :value="$workorder->requisitionWorkorder?->description"/>

              </div>
            </div>
          </template>

          <template x-if="!isNewAsset">
            <div class = "flex flex-col sm:flex-row gap-6">
              <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="asset_id" :required="true">Asset Name</x-page-label>
                  <x-page-input name="asset_id" id="asset_id" value="{{ $workorder->requisitionWorkorder?->asset->name ?? 'N/A' }}" readonly/>
                </div>

                <div class="form-row">
                  <x-page-label for="department_id" :required="true">Department</x-page-label>
                  <x-page-input id="department_id" value="{{ $workorder->requisitionWorkorder?->asset?->department?->name }}" readonly/>
                </div>

                <x-workorders.quantity-field :value="$workorder->request->quantity"/>

              </div>

              <div class = "flex flex-col flex-1 gap-4">

                <x-workorders.supplier-field :suppliers="$suppliers" :workorder="$workorder->requisitionWorkorder?->supplier_id"/>

                <x-workorders.description-field :value="$workorder->requisitionWorkorder?->description"/>
                
              </div>
            </div>
          </template>
        </div>
      </template>

      <template x-if="woType === 'service'">
        <div>
            <x-page-section-header title="Service Details" :breakline="true">
              <x-heroicon-s-wrench-screwdriver class="size-6 text-gray-700"/>
            </x-page-section-header>

            <input type="hidden" name="sub_wo_id" value="{{ $workorder->serviceWorkorder?->id }}">

            <div class="flex flex-col sm:flex-row gap-6">
              <div class="flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="asset_id" :required="true">Asset Name</x-page-label>
                  <x-page-input name="asset_id" id="asset_id" 
                    value="{{ $workorder->serviceWorkorder?->asset->asset_code }} - {{ $workorder->serviceWorkorder?->asset->name }} {{ $workorder->serviceWorkorder?->asset->serial_name ? '(' . $workorder->serviceWorkorder?->asset->serial_name . ')' : '' }}" 
                    readonly/>
                </div>

                <div class="form-row">
                  <x-page-label for="department_id" :required="true">Department</x-page-label>
                  <x-page-input id="department_id" value="{{ $workorder->serviceWorkorder?->asset->department->name }}" readonly/>
                </div>

                <div class="form-row">
                  <x-page-label for="service_type" :required="true">Service Type</x-page-label>
                  <x-page-input id="service_type" value="{{ $workorder->request?->service_type?->label() }}" readonly/>
                </div>

                <x-workorders.quantity-field :value="$workorder->request?->quantity" />

              </div>

              <div class="flex flex-col flex-1 gap-4"
                x-data="{ selectMainType: '{{ old('maintenance_type', $workorder->serviceWorkorder?->maintenance_type?->value ?? '') }}' }">

                <div class="form-row">
                  <x-page-label for="cost" :required="true">Cost</x-page-label>
                  <x-page-input name="cost" id="cost" value="{{ old('cost', $workorder->serviceWorkorder?->cost) }}" type="number"/>
                </div>

                <div class="form-row">
                  <x-page-label for="maintenance_type" :required="true">Maintenance Type</x-page-label>
                  <x-page-select name="maintenance_type" id="maintenance_type" x-model="selectMainType">
                    <option value="" disabled selected>--Select Maitenance Type--</option>
                    @foreach($maintenanceTypes as $type)
                      <option value="{{ $type->value }}"
                        {{ old('maintenance_type', $workorder->serviceWorkorder?->maintenance_type?->value ?? '') == $type->value ? 'selected' : '' }}>
                          {{ $type->label() }}
                      </option>
                    @endforeach
                  </x-page-select>
                </div>

                <div class="form-row" x-show="selectMainType === 'in_house'">
                  <x-page-label for="assigned_to" :required="true">Assigned To</x-page-label>
                  <x-page-select name="assigned_to" id="assigned_to">
                    <option value="" disabled selected>--Select Employee--</option>
                    @foreach($employees as $id=>$name)
                      <option value="{{ $id }}" {{ old('assigned_to', $workorder->serviceWorkorder?->assigned_to?->value ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                      </option>
                    @endforeach
                  </x-page-select>
                </div>

                <div class="form-row" x-show="selectMainType === 'in_house'">
                  <x-page-label for="estimated_hours" :required="true">Estimated Hours</x-page-label>
                  <x-page-input name="estimated_hours" id="estimated_hours" type="number" value="{{ old('quantity', $workorder->serviceWorkorder?->estimated_hours ?? 0 ) }}"/>
                </div>

                <div class="form-row" x-show="selectMainType === 'subcontractor'">
                  <x-page-label for="subcontractor_name" :required="true">Subcontractor Name</x-page-label>
                  <x-page-input id="subcontractor_name" name="subcontractor_name" value="{{ old('subcontractor_name', $workorder->serviceWorkorder?->subcontractor_name) }}"/>
                </div>

                <div class="form-row" x-show="selectMainType === 'subcontractor'">
                  <x-page-label for="subcontractor_details">Subcontractor Details</x-page-label>
                  <x-page-textarea name="subcontractor_details" id="subcontractor_details">{{ old('subcontractor_details', $workorder->serviceWorkorder?->subcontractor_details ?? '') }}</x-page-textarea>
                </div>
              </div>
            </div>

            <x-page-section-header title="Work Details" :breakline="true">
              <x-heroicon-m-wrench class="size-6 text-gray-700"/>
            </x-page-section-header>

            <div class="flex flex-col sm:flex-row gap-6">
              <div class="flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="instructions">Instructions</x-page-label>
                  <x-page-textarea name="instructions" id="instructions">{{ old('instructions', $workorder->serviceWorkorder?->instructions ?? '') }}</x-page-textarea>
                </div>
              </div>

              <div class="flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="accomplishment_report">Accomplisment Report</x-page-label>
                  <x-page-textarea name="accomplishment_report" id="accomplishment_report">{{ old('accomplishment_report', $workorder->serviceWorkorder?->accomplishment_report ?? '') }}</x-page-textarea>
                </div>
              </div>
            </div>
          </div>
      </template>

      <template x-if="woType === 'disposal'">
        <div>
          <x-page-section-header title="Disposal Details" :breakline="true">
            <x-heroicon-s-archive-box class="size-6 text-red-700"/>
          </x-page-section-header>

          <input type="hidden" name="sub_wo_id" value="{{ $workorder->disposalWorkOrder?->id }}">
          <input type="hidden" name="status" value="{{ $workorder->status->value }}">

          <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="asset_id" :required="true">Asset Name</x-page-label>
                <x-page-input name="asset_id" id="asset_id" 
                  value="{{ $workorder->disposalWorkOrder?->asset->asset_code }} - {{ $workorder->disposalWorkOrder?->asset->name }} {{ $workorder->disposalWorkOrder?->asset->serial_name ? '(' . $workorder->disposalWorkOrder?->asset->serial_name . ')' : '' }}" 
                  readonly/>
              </div>

              <div class="form-row">
                <x-page-label for="department_id" :required="true">Department</x-page-label>
                <x-page-input name="department_id" id="department_id" value="{{ $workorder->disposalWorkOrder?->asset->department->name }}" readonly/>
              </div>

              <div class="form-row" :required="true">
                <x-page-label for="quantity">Quantity</x-page-label>
                <x-page-input name="quantity" id="quantity" type="number" value="{{ old('quantity', $workorder->disposalWorkOrder?->quantity ?? $workorder->request->quantity) }}"/>
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                  <x-page-label for="disposal_method" :required="true">Disposal Method</x-page-label>
                  <x-page-select name="disposal_method" id="disposal_method">
                    <option value="" disabled selected>--Select Disposal Method--</option>
                    @foreach($disposalMethods as $method)
                      <option value="{{ $method->value }}" 
                        {{ old('disposal_method', $workorder->disposalWorkOrder?->disposal_method?->value ?? '') == $method->value ? 'selected' : '' }}>
                        {{ $method->label() }}
                      </option>
                    @endforeach
                  </x-page-select>
                </div>

                <div class="form-row">
                  <x-page-label for="disposal_date" :required="$workorder->status->value === 'in_progress'">Disposal Date</x-page-label>
                  <x-page-date-input name="disposal_date" id="disposal_date" value="{{ old('disposal_date', $workorder->disposalWorkOrder?->disposal_date?->format('Y-m-d') ?? '') }}"/>
                </div>

                <div class="form-row">
                  <x-page-label for="reason">Reason</x-page-label>
                  <x-page-textarea name="reason" id="reason">{{ old('reason', $workorder->disposalWorkOrder?->reason ?? '') }}</x-page-textarea>
                </div>
              </div>
            </div>
          </div>
      </template>

      <x-page-edit-submit-button />

    </div>
  </form>
</div>
@endsection