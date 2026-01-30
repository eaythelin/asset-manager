@extends("layouts.pageslayout")
@section("content")

<div class="md:mx-4">
  {{-- Back button + Action buttons --}}
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
    <x-back-link route="assets.index">Return to Assets</x-back-link>
    
    <div class="flex flex-wrap gap-2">
      @can('manage assets')
        <a href="{{ route('assets.edit', $asset->id) }}">
          <x-buttons class="btn-sm sm:btn-md">
            <x-heroicon-o-pencil-square class="size-4"/>
            Edit Asset
          </x-buttons>
        </a>
        <x-buttons 
          class="disposeBtn btn-sm sm:btn-md" 
          onclick="disposeAsset.showModal()"
          data-route="{{ route('assets.dispose', $asset->id) }}">
          <x-heroicon-o-trash class="size-4"/>
          Dispose
        </x-buttons>
      @endcan
    </div>
  </div>

  <div class="bg-white p-4 rounded-2xl shadow-xl mb-4">
    <div class="flex flex-col sm:flex-row items-center gap-4">
      {{-- Image --}}
      <div class="size-32 flex-shrink-0">
        @if($asset->image_path)
          <img src="{{ Storage::url($asset->image_path) }}" alt="{{ $asset->name }}" class="w-full h-full object-cover rounded-lg shadow-xl">
        @else
          <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
            <x-heroicon-o-photo class="size-16 text-gray-400" />
          </div>
        @endif
      </div>

      <div class="flex-grow text-center sm:text-left">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $asset->name }}</h2>
        <div class="flex items-center justify-center sm:justify-start gap-2 text-sm mt-1">
          <x-heroicon-s-hashtag class="text-gray-500 size-4"/>
          <span class="text-gray-500">Asset Code:</span>
          <span class="font-semibold text-gray-700">{{ $asset->asset_code }}</span>
        </div>
        <div class="flex items-center justify-center sm:justify-start gap-2 text-sm mt-1">
          <x-heroicon-s-identification class="text-gray-500 size-4"/>
          <span class="text-gray-500">Serial Name:</span>
          <span class="font-semibold text-gray-700">{{ $asset->serial_name ?? 'N/A' }}</span>
        </div>
      </div>

      <div class="flex flex-col gap-2">
        @if($asset->computed_status === "expired")
          <span class="badge badge-warning text-white font-medium text-sm p-3 tooltip tooltip-top"
            data-tip="Asset has reached the end of its lifecycle">Expired</span>
        @elseif($asset->computed_status === "disposed")
          <span class="badge badge-error text-white font-medium text-sm p-3">Disposed</span>
        @elseif($asset->computed_status === "under_service")
          <span class="badge badge-info text-white font-medium text-sm p-3">Under Service</span>
        @elseif($asset->computed_status === "active")
          <span class="badge badge-success text-white font-medium text-sm p-3">Active</span>
        @endif
      </div>
    </div>
  </div>

  <div role="tablist" class="tabs tabs-lifted">
    {{-- Overview Tab --}}
    <input type="radio" name="asset_tabs" role="tab" class="tab" aria-label="Overview" checked />
    <div role="tabpanel" class="tab-content bg-base-200 border-base-300 rounded-box p-6">
      {{-- General & Assignment Details --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-white p-4 rounded-2xl shadow-xl">
          <div class="flex flex-row items-center gap-2 mb-2">
            <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
            <p class="text-lg font-semibold">General Details</p>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-sm text-gray-500">Category</p>
              <p class="font-semibold text-gray-700">{{ $asset->category->name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Subcategory</p>
              <p class="font-semibold text-gray-700">{{ $asset->subCategory?->name ?? 'N/A'}}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Description</p>
              <p class="font-semibold text-gray-700">{{ $asset->description ?? 'N/A' }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-4 rounded-2xl shadow-xl">
          <div class="flex flex-row items-center gap-2 mb-2">
            <x-heroicon-s-user-group class="size-6 text-green-700"/>
            <p class="text-lg font-semibold">Assignment Details</p>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-sm text-gray-500">Department</p>
              <p class="font-semibold text-gray-700">{{ $asset->department->name }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Custodian</p>
              <p class="font-semibold text-gray-700">{{ $asset->custodian->full_name ?? 'No Custodian Assigned'}}</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Financial Details --}}
      <div class="bg-white p-4 rounded-2xl shadow-xl mb-4">
        <div class="flex flex-row items-center gap-2 mb-2">
          <x-heroicon-s-currency-dollar class="size-6 text-yellow-400"/>
          <p class="text-lg font-semibold">Financial Details</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="space-y-3">
            <div>
              <p class="text-sm text-gray-500">Acquisition Date</p>
              <p class="font-semibold text-gray-700">{{ $asset->acquisition_date?->format('F j, Y') ?? 'N/A'}}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Useful Life in Years</p>
              <p class="font-semibold text-gray-700">{{ $asset->useful_life_in_years ?? 'N/A'}}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">End of Life Date</p>
              <p class="font-semibold text-gray-700">{{ $asset->end_of_life_date?->format('F j, Y') ?? 'N/A'}}</p>
            </div>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-sm text-gray-500">Cost</p>
              <p class="font-semibold text-gray-700">₱{{ number_format($asset->cost ?? 0, 2) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Salvage Value</p>
              <p class="font-semibold text-gray-700">₱{{ number_format($asset->salvage_value ?? 0, 2) }}</p>
            </div>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-sm text-gray-500">Current Book Value</p>
              <p class="font-semibold text-gray-700">₱{{ number_format($asset->book_value ?? 0, 2) }}</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Misc Details --}}
      <div class="bg-white p-4 rounded-xl shadow-xl">
        <div class="flex flex-row items-center gap-2 mb-2">
          <x-heroicon-s-clipboard-document-list class="size-6 text-gray-600"/>
          <p class="text-lg font-semibold">Misc. Details</p>
        </div>
        <div class="space-y-3">
          <div>
            <p class="text-sm text-gray-500">Supplier</p>
            <p class="font-semibold text-gray-700">{{ $asset->supplier->name ?? 'N/A'}}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- History Tab --}}
    <input type="radio" name="asset_tabs" role="tab" class="tab" aria-label="History" />
    <div role="tabpanel" class="tab-content bg-base-200 border-base-300 rounded-box p-6">
      <div class="bg-white p-4 rounded-2xl shadow-xl">
        <div class="flex flex-row items-center gap-2 mb-4">
          <x-heroicon-s-clock class="size-6 text-purple-700"/>
          <h3 class="text-lg font-semibold">Asset History</h3>
        </div>
        {{-- Timeline placeholder --}}
        <div class="text-center py-8">
          <x-heroicon-o-clock class="size-16 text-gray-300 mx-auto mb-3"/>
          <p class="text-gray-500">No history recorded yet</p>
          <p class="text-sm text-gray-400 mt-1">Asset activities will appear here</p>
        </div>
      </div>
    </div>
  </div>
</div>

@include('modals.assets-modals.dispose-asset-modal')
@endsection

@section('scripts')
  @vite('resources/js/assets/dispose-asset/disposeAsset.js')
@endsection