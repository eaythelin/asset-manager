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
      <div class = "flex flex-row items-center gap-2 mb-4">
        <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
        <p class="text-lg font-bold">General Information</p>
      </div>

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
          <hr class="border-gray-300 m-5">
          <div class="flex flex-row items-center gap-2 mb-4">
            <x-heroicon-s-archive-box  class="size-6 text-green-700"/>
            <p class="text-lg font-bold">Requisition Details</p>
          </div>
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
          <hr class="border-gray-300 m-5">
          <div class="flex flex-row items-center gap-2 mb-4">
            <x-heroicon-s-wrench-screwdriver class="size-6 text-gray-700"/>
            <p class="text-lg font-bold">Service Details</p>
          </div>
          <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
              <div class = "form-row">
                <x-page-label for="asset_id">Asset Name</x-page-label>
                <x-page-select name="asset_id" id="asset_id">
                <option value="" disabled selected {{ old('asset_id') ? '' : 'selected' }}>--Select Asset--</option>
                @foreach($assets as $asset)
                  <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                    {{ $asset->asset_code }} - {{ $asset->name }} 
                    @if($asset->serial_name)
                      ({{ $asset->serial_name }})
                    @endif
                  </option>
                @endforeach
                </x-page-select>
              </div>
              
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
          <hr class="border-gray-300 m-5">
          <div class="flex flex-row items-center gap-2 mb-4">
            <x-heroicon-s-archive-box class="size-6 text-red-700"/>
            <p class="text-lg font-bold">Disposal Details</p>
          </div>

          <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
              <div class = "form-row">
                <x-page-label for="asset_id">Asset Name</x-page-label>
                <x-page-select name="asset_id" id="asset_id">
                <option value="" disabled selected {{ old('asset_id') ? '' : 'selected' }}>--Select Asset--</option>
                @foreach($assets as $asset)
                  <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                    {{ $asset->asset_code }} - {{ $asset->name }} 
                    @if($asset->serial_name)
                      ({{ $asset->serial_name }})
                    @endif
                  </option>
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
        <div x-init="
            {{-- nextTick = basically says to only run this code once the DOM is fully updated --}}
            $nextTick(() => {
              const input = document.getElementById('attachments');
              if (input && !input._pond) {
                FilePond.registerPlugin(FilePondPluginImagePreview);
                input._pond = FilePond.create(input, {
                  allowMultiple: true,
                  maxFiles: 5,
                  maxFileSize: '10MB',
                  acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                  credits: false,
                  instantUpload: false,
                  storeAsFile: true
                });
              }
            })
          ">
          <hr class="border-gray-300 m-5">
          <div class="flex flex-row items-center gap-2 mb-4">
            <x-heroicon-s-arrow-up-tray  class="size-6 text-green-700"/>
            <p class="text-lg font-bold">Uploads</p>
          </div>

          <div class="flex flex-col gap-4">
            <div class="form-row">
              <x-page-label for="attachments">Attachments</x-page-label>
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
  @vite('resources/js/requests/create-requests/getSubcategories.js')
@endsection