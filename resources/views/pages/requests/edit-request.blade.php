@extends('layouts.pageslayout')
@section('content')

<x-session-error />

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="requests.index">Return to Requests</x-back-link>
  </div>

  <x-validation-error />

  <form method="POST" action="{{ route('requests.update', $requestModel->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="bg-white p-4 rounded-2xl shadow-2xl mt-4" x-data="{selectedRequestType: '{{ old('type', $requestModel->type->value) }}'}">
        <x-page-section-header title="General Information">
            <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
        </x-page-section-header>

        <div class = "flex flex-col sm:flex-row gap-6">
            <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                    <x-page-label for="request_code" :required="true">Request Code</x-page-label>
                    <x-page-input value="{{ $requestModel->request_code }}" name="request_code" id="request_code" readonly/>
                </div>
            </div>
            
            <div class = "flex flex-col flex-1 gap-4">
                <div class="form-row">
                    <x-page-label for="type" :required="true">Request Type</x-page-label>
                    <x-page-select name="type" id="type" x-model="selectedRequestType">
                        <option value="" disabled>--Select Type--</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->value }}" {{ old('type', $requestModel->type->value) == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
                        @endforeach
                    </x-page-select>
                </div>
            </div>
        </div>

        <template x-if="selectedRequestType === 'requisition'">
            {{-- x-init will run the code whenever this template appears --}}
            <div x-init="
                    $nextTick(() => {
                    const categorySelect = document.getElementById('category');
                    const subcategorySelect = document.getElementById('subcategory');
                    const currentSubcategoryId = subcategorySelect?.dataset.currentSubcategory || null;
                    
                    if(categorySelect?.value){
                        loadSubcategories(categorySelect.value, currentSubcategoryId);
                    }

                    categorySelect?.addEventListener('change', function(){
                        loadSubcategories(this.value);
                    });
                    })
                ">
                <x-page-section-header title="Requisition Details" :breakline="true">
                    <x-heroicon-s-archive-box  class="size-6 text-green-700"/>
                </x-page-section-header>

                <div class = "flex flex-col sm:flex-row gap-6">
                    <div class = "flex flex-col flex-1 gap-4">
                        <div class = "form-row">
                            <x-page-label for="asset_name" :required="true">Asset Name</x-page-label>
                            <x-page-input name="asset_name" id="asset_name" value="{{ old('asset_name',$requestModel->asset_name) }}"/>
                        </div>

                        <div class="form-row">
                            <x-page-label for="category" :required="true">Category</x-page-label>
                            <x-page-select name="category" id="category">
                                <option value="" disabled selected {{ old('category') ? '' : 'selected' }}>--Select Category--</option>
                                @foreach($categories as $id=>$name)
                                    <option value="{{ $id }}" {{ old('category', $requestModel->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </x-page-select>
                        </div>

                        <div class = "form-row">
                            <x-page-label for="subcategory">Subcategory</x-page-label>
                            <x-page-select name="subcategory" id="subcategory" data-current-subcategory="{{ $requestModel->sub_category_id ?? '' }}" disabled>
                                <option value="" disabled>--Select Subcategory--</option>
                            </x-page-select>
                        </div>
                    </div>

                    <div class = "flex flex-col flex-1 gap-4">
                        <div class="form-row">
                            <x-page-label for="description">Description</x-page-label>
                            <x-page-textarea name="description" id="description">{{ old('description', $requestModel?->description) }}</x-page-textarea>
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
                        <x-request-asset-select :assets="$assets" :selected="$requestModel->asset_id"/>

                        <div class="form-row">
                            <x-page-label for="service_type" :required="true">Service Type</x-page-label>
                            <x-page-select name="service_type" id="service_type">
                                <option value="" disabled selected {{ old('service_type') ? '' : 'selected' }}>--Select Service Type--</option>
                                @foreach($serviceTypes as $serviceType)
                                    <option 
                                        value="{{ $serviceType->value }}" 
                                        {{ old('service_type', $requestModel->service_type?->value) == $serviceType->value ? 'selected' : '' }}>
                                        {{ $serviceType->label() }}
                                    </option>
                                @endforeach
                            </x-page-select>
                        </div>
                    </div>

                    <div class = "flex flex-col flex-1 gap-4">
                        <div class="form-row">
                            <x-page-label for="description">Description</x-page-label>
                            <x-page-textarea name="description" id="description">{{ old('description', $requestModel?->description) }}</x-page-textarea>
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
                        <x-request-asset-select :assets="$assets" :selected="$requestModel->asset_id"/>
                        <div class="form-row">
                            <x-page-label for="condition" :required="true">Condition</x-page-label>
                            <x-page-select name="condition" id="condition">
                                <option value="" disabled selected {{ old('condition') ? '' : 'selected' }}>--Select Asset Condition--</option>
                                @foreach($disposalConditions as $condition)
                                    <option 
                                        value="{{ $condition->value }}" 
                                        {{ old('condition', $requestModel->condition?->value) == $condition->value ? 'selected' : '' }}>
                                        {{ $condition->label() }}
                                    </option>
                                @endforeach
                            </x-page-select>
                        </div>
                    </div>

                    <div class = "flex flex-col flex-1 gap-4">
                        <div class="form-row">
                            <x-page-label for="description">Description</x-page-label>
                            <x-page-textarea name="description" id="description">{{ old('description', $requestModel?->description) }}</x-page-textarea>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="selectedRequestType !== ''">
            <div x-init="$nextTick(() => initFilePond())">
                <x-page-section-header title="Uploads" :breakline="true">
                    <x-heroicon-s-arrow-up-tray  class="size-6 text-green-700"/>
                </x-page-section-header>

                @if($requestModel->files->count() > 0)
                    <p class="text-sm font-medium text-gray-600 mb-2 ml-3">Existing Attachments</p>
                    @foreach($requestModel->files as $file)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg mb-3 bg-gray-50 px-10 mx-10">
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-paper-clip class="size-4 text-gray-500"/>
                                <span class="text-sm text-gray-700">{{ $file->original_name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(in_array(pathinfo($file->original_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'pdf']))
                                    <a href="{{ route('requests.attachments', $file->id) }}" target="_blank" class="text-sm text-blue-600 hover:underline">View</a>
                                @endif
                                <a href="{{ route('requests.attachments', $file->id) }}" download="{{ $file->original_name }}" class="text-sm text-green-600 hover:underline">Download</a>
                                <label class="flex items-center gap-1 text-sm text-red-500 cursor-pointer">
                                    <input type="checkbox" name="delete_files[]" value="{{ $file->id }}" class="accent-red-500">
                                    Delete
                                </label>
                            </div>
                        </div>
                    @endforeach

                    @if($requestModel->files->count() >= 5)
                        <p class="text-sm text-red-500 mb-2 ml-3">Maximum attachments reached. Please delete some before adding new ones.</p>
                    @endif
                @endif

                @if($requestModel->files->count() < 5)
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
                @endif
                <div class = "flex justify-end mt-4">
                    <x-buttons type="submit" class="w-full md:w-auto">
                        <x-heroicon-s-plus class="size-5"/>
                        Edit Request
                    </x-buttons>
                </div>
            </div>
        </template>
    </div>
  </form>
</div>
@endsection

@section("scripts")
    @vite('resources/js/requests/edit-requests/getReqEditSubcategories.js')
    @vite('resources/js/requests/reqFilepond.js')
@endsection