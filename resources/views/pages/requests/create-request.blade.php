@extends('layouts.pageslayout')
@section('content')

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="requests.index">Return to Requests</x-back-link>
  </div>

  <x-validation-error />

  <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4" x-data="{selectedRequestType: '{{ old('type', '') }}'}">
      <x-page-section-header title="General Information">
        <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
      </x-page-section-header>

      <div class = "flex flex-col sm:flex-row gap-6">
        {{-- Left Column!! --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="request_code" :required="true">Request Code</x-page-label>
            <x-page-input value="{{ $nextCode }}" name="request_code" id="request_code" readonly/>
          </div>
        </div>
        {{-- Right Column!! --}}
        <div class = "flex flex-col flex-1 gap-4">
          <div class="form-row">
            <x-page-label for="type" :required="true">Request Type</x-page-label>
            <x-page-select name="type" id="type" x-model="selectedRequestType">
              <option value="" disabled selected {{ old('type') ? '' : 'selected' }}>--Select Type--</option>
              @foreach($requestTypes as $type)
                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
              @endforeach
            </x-page-select>
          </div>
        </div>
      </div>

      <template x-if="selectedRequestType === 'requisition'">
        <div>
          <x-page-section-header title="Requisition Details" :breakline="true">
            <x-heroicon-s-archive-box  class="size-6 text-green-700"/>
          </x-page-section-header>

          <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
              <div class = "form-row">
                <x-page-label for="asset_name" :required="true">Asset Name</x-page-label>
                <x-page-input name="asset_name" id="asset_name" value="{{ old('asset_name') }}"/>
              </div>

              <div class = "form-row">
                <x-page-label for="category" :required="true">Category</x-page-label>
                <x-page-select name="category" id="category">
                  <option value="" disabled selected {{ old('category') ? '' : 'selected' }}>--Select Category--</option>
                  @foreach($categories as $id=>$name)
                    <option value="{{ $id }}" {{ old('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                  @endforeach
                </x-page-select>
              </div>

              <div class = "form-row">
                <x-page-label for="subcategory">Subcategory</x-page-label>
                <x-page-select name="subcategory" id="subcategory" disabled>
                  <option value="" disabled selected>--Select Subcategory--</option>
                </x-page-select>
              </div>
            </div>

            {{-- Right Column!! --}}
            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="description">Description</x-page-label>
                <x-page-textarea name="description" id="description">{{ old('description') }}</x-page-textarea>
              </div>
            </div>
          </div>
        </div>
      </template>
      
      <template x-if="selectedRequestType === 'service'">
        <div>
          <x-page-section-header title="Service Details" :breakline="true">
            <x-heroicon-s-wrench-screwdriver class="size-6 text-gray-700"/>
          </x-page-section-header>
          <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
              <x-request-asset-select :assets="$assets"/>
              
              <div class="form-row">
                <x-page-label for="service_type" :required="true">Service Type</x-page-label>
                <x-page-select name="service_type" id="service_type">
                  <option value="" disabled selected {{ old('service_type') ? '' : 'selected' }}>--Select Service Type--</option>
                  @foreach($serviceTypes as $serviceType)
                    <option value="{{ $serviceType->value }}" {{ old('service_type') == $serviceType->value ? 'selected' : '' }}>{{ $serviceType->label() }}</option>
                  @endforeach
                </x-page-select>
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="description">Description</x-page-label>
                <x-page-textarea name="description" id="description">{{ old('description') }}</x-page-textarea>
              </div>  
            </div>
          </div>
        </div>
      </template>

      <template x-if="selectedRequestType === 'disposal'">
        <div>
          <x-page-section-header title="Disposal Details" :breakline="true">
            <x-heroicon-s-archive-box class="size-6 text-red-700"/>
          </x-page-section-header>

          <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
              <x-request-asset-select :assets="$assets"/>

              <div class="form-row">
                <x-page-label for="condition" :required="true">Condition</x-page-label>
                <x-page-select name="condition" id="condition">
                  <option value="" disabled selected {{ old('condition') ? '' : 'selected' }}>--Select Asset Condition--</option>
                  @foreach($disposalConditions as $condition)
                    <option value="{{ $condition->value }}" {{ old('condition') == $condition->value ? 'selected' : '' }}>{{ $condition->label() }}</option>
                  @endforeach
                </x-page-select>  
              </div>
            </div>

            <div class = "flex flex-col flex-1 gap-4">
              <div class="form-row">
                <x-page-label for="description">Description</x-page-label>
                <x-page-textarea name="description" id="description">{{ old('description') }}</x-page-textarea>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template x-if="selectedRequestType !== ''">
        {{-- x-init = run this code when this div gets added to the page --}}
        <div x-init="$nextTick(() => initFilePond())">
          <x-page-section-header title="Uploads" :breakline="true">
            <x-heroicon-s-arrow-up-tray  class="size-6 text-green-700"/>
          </x-page-section-header>

          <div class="flex flex-col gap-4">
            <div class="form-row">
              <x-page-label for="attachments">
                Attachments
                <span class="text-xs text-gray-500 align-super tooltip tooltip-info" data-tip="Max 5 files • 10MB per file">?</span>
              </x-page-label>
              <div class="w-full">
                <input type="file" class="filepond" name="attachments[]" id="attachments" accept="image/*,.pdf,.doc,.docx" multiple>
              </div>
            </div>
          </div>
          <div class = "flex justify-end mt-4">
            <x-buttons type="submit" class="w-full md:w-auto">
              <x-heroicon-s-plus class="size-5"/>
              Create Request
            </x-buttons>
          </div>
        </div>
      </template>
    </div>
  </form>
</div>
@endsection

@section('scripts')
  @vite('resources/js/requests/create-requests/getReqSubcategories.js')
  @vite('resources/js/requests/reqFilepond.js')
@endsection