@extends('layouts.pageslayout')
@section('content')

<x-session-error />

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="requests.index">Return to Requests</x-back-link>
  </div>

  <x-validation-error />

  <form method="POST" action="{{ route('requests.update', $requestModel->id) }}">
    @csrf
    @method('PUT')
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4">
      <x-page-section-header title="Repair and Maintenance Request Form">
          <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
      </x-page-section-header>

      <div class = "flex flex-col sm:flex-row gap-6">
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="requisitioner">Requisitioner's name</x-page-label>
            <input value="{{ $requestModel->requestedBy->id }}" name="requisitioner" id="requisitioner" hidden>
            <x-page-input value="{{ $requestModel->requestedBy->name }}" readonly/>
          </div>

          <div class="form-row">
            <x-page-label for="asset" :required="true">Equipment/Vehicle</x-page-label>
            <x-page-select name="asset" id="asset">
              <option value="" disabled selected>--Select Asset--</option>
              @foreach($assets as $asset)
                <option value="{{ $asset->id }}" {{ old('asset', $requestModel->asset->id) == $asset->id ? 'selected' : '' }}>
                  {{ $asset->asset_code }} - {{ $asset->name }}
                  @if($asset->serial_name)
                    ({{ $asset->serial_name }})
                  @endif
                </option>
              @endforeach
            </x-page-select>
          </div>

          <div class="form-row">
            <x-page-label for="description" :required="true">Request Description/Plate No.</x-page-label>
            <x-page-textarea name="description" id="description">{{ old('description', $requestModel->description) }}</x-page-textarea>
          </div>
        </div>

        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="control_number">Control Number</x-page-label>
            <x-page-input value="{{ $requestModel->control_number }}" name="control_number" id="control_number" readonly/>
          </div>

          <div class="form-row">
            <x-page-label for="department">Sec/Dept</x-page-label>
            <x-page-input value="{{ auth()->user()->employee->department->name }}" readonly/>
            <input type="hidden" value="{{ auth()->user()->employee->department->id }}" name="department" id="department">
          </div>

          <div class="form-row">
            <x-page-label for="date">Date</x-page-label>
            <x-page-input value="{{ now()->format('M d, Y') }}" name="date" id="date" disabled/>
          </div>

          <div class="form-row">
            <x-page-label :required="true" for="request_type">Type of Request</x-page-label>
            <div class="flex gap-6">
              @foreach ($requestTypes as $type)
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="request_type" value="{{ $type->value }}"
                    {{ old('request_type', $requestModel->request_type->value) == $type->value ? 'checked' : '' }}
                    class="checkbox rounded-none checkbox-sm checkbox-primary">
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
          <x-page-input value="{{ $requestModel->requestedBy->name }}" name="requested_by" id="requested_by" disabled/>
        </div>

        <div class="form-row flex-1">
          <x-page-label for="approved_by">Request Approved By</x-page-label>
          <x-page-input value="" name="approved_by" id="approved_by" disabled/>
        </div>
      </div>

      <x-page-edit-submit-button />
    </div>
  </form>
</div>
@endsection
