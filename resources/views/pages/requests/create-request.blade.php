@extends('layouts.pageslayout')
@section('content')

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="requests.index">Return to Requests</x-back-link>
  </div>

  <x-validation-error />
  <x-session-error />

  <form method="POST" action="{{ route('requests.store') }}">
    @csrf
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4">
      <x-page-section-header title="Repair and Maintenance Request Form (RMRF)">
        <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
      </x-page-section-header>

      <div class = "flex flex-col sm:flex-row gap-6">
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="control_number">Control Number</x-page-label>
            <x-page-input value="{{ $controlNumber }}" name="control_number" id="control_number" readonly/>
          </div>

          <div class="form-row">
            <x-page-label for="requisitioner">Requisitioner</x-page-label>
            <input value="{{ auth()->user()->id }}" name="requisitioner" id="requisitioner" hidden>
            <x-page-input value="{{ auth()->user()->name }}" readonly/>
          </div>

          <div class="form-row">
            <x-page-label for="asset" :required="true">Equipment/Vehicle</x-page-label>
            <x-page-select name="asset" id="asset">
              <option value="" disabled selected>--Select Asset--</option>
              @foreach($assets as $asset)
                <option value="{{ $asset->id }}" {{ old('asset') == $asset->id ? 'selected' : '' }}>
                  {{ $asset->asset_code }} - {{ $asset->name }}
                  @if($asset->serial_name)
                    ({{ $asset->serial_name }})
                  @endif
                </option>
              @endforeach
            </x-page-select>
          </div>

          <div class="form-row">
            <x-page-label for="description" :required="true">Description/Plate No.</x-page-label>
            <x-page-textarea name="description" id="description">{{ old('description') }}</x-page-textarea>
          </div>
        </div>

        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="department" :required="true">Department</x-page-label>
            <x-page-select name="department" id="department">
              <option value="" disabled selected>--Select Department--</option>
              @foreach($departments as $id=>$name)
                <option value="{{ $id }}" {{ old('department') == $id ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </x-page-select>
          </div>

          <div class="form-row">
            <x-page-label for="date">Date</x-page-label>
            <x-page-input value="{{ now()->format('M d, Y') }}" name="date" id="date" disabled/>
          </div>

          <div class="form-row">
            <x-page-label for="request_type" :required="true">Request Type</x-page-label>
            <x-page-select name="request_type" id="request_type">
              <option value="" disabled selected>--Select Request Type--</option>
              @foreach ($requestTypes as $type)
                <option value="{{ $type->value }}" {{ old('request_type') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
              @endforeach
            </x-page-select>
          </div>
        </div>
      </div>

      <x-page-create-submit-button>Submit</x-page-create-submit-button>
    </div>
  </form>
</div>
@endsection
