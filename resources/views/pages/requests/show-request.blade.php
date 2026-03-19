@extends('layouts.pageslayout')
@section('content')
<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="requests.index">Return to Requests</x-back-link>
  </div>

  <x-view-page-header-card>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div class="flex items-start gap-4">
        <div class="hidden sm:flex items-center justify-center size-12 rounded-xl bg-indigo-50 text-indigo-600">
          <x-heroicon-s-document class="size-6"/>
        </div>

        <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Request ID</span>
              <span class="text-xs py-0.5 px-2 bg-gray-100 text-gray-600 r  ounded-md font-mono">
                #{{ $requestModel->request_code }}
              </span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 leading-tight">
              {{ $requestModel->type->label() }}
            </h2>
        </div>
      </div>

      <div class="flex flex-col items-end gap-2">
        <span class="badge {{ $requestModel->status->badgeClass() }} text-white font-medium text-sm">
          {{ $requestModel->status->label() }}
        </span>
        <span class="text-xs text-gray-400 flex items-center gap-1">
          <x-heroicon-c-clock class="size-3"/>
          Updated {{ $requestModel->updated_at->diffForHumans() }}
        </span>
      </div>
    </div>
  </x-view-page-header-card>

  <div role="tablist" class="tabs tabs-lifted">
    {{-- Overview Tab --}}
    <input type="radio" name="request_tabs" role="tab" class="tab" aria-label="Overview" checked />
    <div role="tabpanel" class="tab-content bg-base-200 border-base-300 rounded-box p-6">
      @if($requestModel->is_new_asset)
        <div class="bg-white p-4 rounded-2xl shadow-xl">
          <div class="flex flex-row items-center gap-2 mb-2">
            <x-heroicon-s-document-text class="size-6 text-green-700"/>
            <p class="text-lg font-semibold">Request Details</p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-3">
              <x-detail-item label="Asset Name" :value="$requestModel->asset_name"/>
              <x-detail-item label="Category" :value="$requestModel->category->name"/>
              <x-detail-item label="Subcategory" :value="$requestModel->subCategory?->name"/>
            </div>
            <div class="space-y-3">
              <x-detail-item label="Quantity" :value="$requestModel->quantity"/>
              <x-detail-item label="Description" :value="$requestModel->description"/>
            </div>
          </div>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
              <p class="text-lg font-semibold">Asset Details</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="size-48 shrink-0 mx-auto">
                @if($requestModel->asset->image_path)
                  <img src="{{ Storage::url($requestModel->asset->image_path) }}" alt="{{ $requestModel->asset->name }}" class="w-full h-full object-cover rounded-lg shadow-xl">
                @else
                  <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-photo class="size-16 text-gray-400" />
                  </div>
                @endif
              </div>
              <div class="space-y-3">
                <x-detail-item label="Asset Code" :value="$requestModel->asset->asset_code"/>
                <x-detail-item label="Asset Name" :value="$requestModel->asset->name"/>
                <x-detail-item label="Serial Name" :value="$requestModel->asset->serial_name"/>
              </div>
              <div class="space-y-3">
                <x-detail-item label="Department" :value="$requestModel->asset->department->name"/>
                <x-detail-item label="Category" :value="$requestModel->asset->category->name"/>
                <x-detail-item label="Current Quantity" :value="$requestModel->asset->quantity"/>
              </div>
            </div>
          </div>

          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-document-text class="size-6 text-green-700"/>
              <p class="text-lg font-semibold">Request Details</p>
            </div>
            <div class="space-y-3">
              <x-detail-item label="Requested Quantity" :value="$requestModel->quantity"/>
              @if($requestModel->type->value === "disposal")
                <x-detail-item label="Condition" :value="$requestModel->condition?->label()"/>
              @elseif($requestModel->type->value === "service")
                <x-detail-item label="Service Type" :value="$requestModel->service_type?->label()"/>
              @endif
              <x-detail-item label="Description" :value="$requestModel->description"/>
            </div>
          </div>
        </div>
      @endif
    </div>

    <input type="radio" name="request_tabs" role="tab" class="tab" aria-label="Attachments" />
    <div role="tabpanel" class="tab-content bg-base-200 border-base-300 rounded-box p-6">
      <div class="bg-white p-4 rounded-2xl shadow-xl">
        <div class="flex flex-row items-center gap-2 mb-2">
          <x-heroicon-s-document-arrow-down class="size-6 text-purple-700"/>
          <p class="text-lg font-semibold">Attachments</p>
        </div>
        @if($requestModel->files->count() > 0)
          @foreach($requestModel->files as $file)
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl mb-2 bg-white hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="p-2 bg-gray-100 rounded-lg">
                        <x-heroicon-s-document-text class="size-5 text-gray-500"/>
                    </div>
                    <div class="flex flex-col truncate">
                        <span class="text-sm font-medium text-gray-900 truncate">
                            {{ $file->original_name }}
                        </span>
                        <span class="text-xs text-gray-500 uppercase">
                            {{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    @if(in_array(pathinfo($file->original_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'pdf']))
                        <a href="{{ route('requests.attachments', $file->id) }}" 
                          target="_blank" 
                          class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                          title="View File">
                            <x-heroicon-s-eye class="size-5"/>
                        </a>
                    @endif
                    
                    <a href="{{ route('requests.attachments', $file->id) }}" 
                      download="{{ $file->original_name }}" 
                      class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all"
                      title="Download">
                        <x-heroicon-s-arrow-down-tray class="size-5"/>
                    </a>
                </div>
            </div>
          @endforeach
        @else
          <div class="text-center py-8">
            <x-heroicon-s-document-arrow-down class="size-16 text-gray-300 mx-auto mb-3"/>
            <p class="text-gray-500">No Attachments</p>
            <p class="text-sm text-gray-400 mt-1">Request attachments will appear here</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection