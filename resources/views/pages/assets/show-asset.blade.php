@extends("layouts.pageslayout")
@section("content")

<div class="md:mx-4">
  {{-- Back button + Action buttons --}}
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
    <x-back-link route="assets.index">Return to Assets</x-back-link>
    
    <div class="flex flex-wrap gap-2">
      @if(auth()->user()->can('manage assets') && $asset->status->value !== "disposed")
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
      @endif
    </div>
  </div>

  <x-view-page-header-card>
    <div class="flex flex-col sm:flex-row items-center gap-4">
      {{-- Image --}}
      <div class="size-32 shrink-0">
        @if($asset->image_path)
          <img src="{{ Storage::url($asset->image_path) }}" alt="{{ $asset->name }}" class="w-full h-full object-cover rounded-lg shadow-xl">
        @else
          <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
            <x-heroicon-o-photo class="size-16 text-gray-400" />
          </div>
        @endif
      </div>

      <div class="grow text-center sm:text-left">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $asset->name }}</h2>
        <div class="flex items-center justify-center sm:justify-start gap-2 text-sm mt-1">
          <x-heroicon-s-hashtag class="text-gray-500 size-4"/>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Asset Code:</span>
          <span class="text-sm py-0.5 px-2 bg-gray-100 text-gray-600 rounded-md font-mono">
            {{ $asset->asset_code }}
          </span>
        </div>
        <div class="flex items-center justify-center sm:justify-start gap-2 text-sm mt-1">
          <x-heroicon-s-identification class="text-gray-500 size-4"/>
          <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Serial Name:</span>
          <span class="text-sm py-0.5 px-2 bg-gray-100 text-gray-600 rounded-md font-mono">
            {{ $asset->serial_name ?? 'N/A' }}
          </span>
        </div>
      </div>

      <div class="flex flex-col items-center sm:items-end gap-2">
        <span class="badge {{ $asset->status->badgeColor() }} text-white font-medium text-sm p-3">
          {{ $asset->status->label() }}
        </span>
        <span class="text-xs text-gray-400 flex items-center gap-1">
          <x-heroicon-c-clock class="size-3"/>
          Updated {{ $asset->updated_at->diffForHumans() }}
        </span>
      </div>
    </div>
  </x-view-page-header-card>

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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
              <x-detail-item label="Category" :value="$asset->category->name"/>
              <x-detail-item label="Subcategory" :value="$asset->subCategory?->name"/>
            </div>
            <div class="space-y-3">
              <x-detail-item label="Quantity" :value="$asset->quantity"/>
              <x-detail-item label="Description" :value="$asset->description"/>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-4 rounded-2xl shadow-xl">
          <div class="flex flex-row items-center gap-2 mb-2">
            <x-heroicon-s-user-group class="size-6 text-green-700"/>
            <p class="text-lg font-semibold">Assignment Details</p>
          </div>
          <div class="space-y-3">
            <x-detail-item label="Department" :value="$asset->department->name"/>
            <x-detail-item label="Custodian" :value="$asset->custodian?->full_name"/>
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
            <x-detail-item label="Acquisition Date" :value="$asset->acquisition_date?->format('F j, Y')"/>
            <x-detail-item label="Useful Life in Years" :value="$asset->useful_life_in_years "/>
            <x-detail-item label="End of Life Date" :value="$asset->end_of_life_date?->format('F j, Y')"/>
          </div>
          <div class="space-y-3">
            <x-detail-item label="Cost" :value="'₱' . number_format($asset->cost ?? 0, 2)" />
            <x-detail-item label="Salvage Value" :value="'₱' . number_format($asset->salvage_value ?? 0, 2)" />
          </div>
          <div class="space-y-3">
            <x-detail-item label="Accumulated Depreciation" :value="'₱' . number_format($asset->accumulated_depreciation ?? 0, 2)" />
            <x-detail-item label="Net Book Value" :value="'₱' . number_format($asset->book_value ?? 0, 2)" />
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
          <x-detail-item label="Supplier" :value="$asset->supplier?->name"/>
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
        @if($hasWorkorders)
         <x-tables :columnNames="$columns" :centeredColumns="[0,2]">
          <tbody class = "divide-y divide-gray-400">  
            @foreach($history as $record)
              <tr>
                <th class = "p-3 text-center">{{ $record->workorder->workorder_code }}</th>
                <x-td>{{ $record->workorder->workorder_type->label()}}</x-td>
                <x-td class="text-center"> <span class="badge {{ $record->workorder->status->badgeClass() }} text-white font-medium text-sm"> {{ $record->workorder->status->label() }} </span> </x-td>
                <x-td>{{ $record->workorder->start_date?->format('F j, Y') }}</x-td>
                <x-td>{{ $record->workorder->end_date?->format('F j, Y') }}</x-td>
                <x-td>
                  @if($record->workorder->workorder_type->value === 'disposal')
                    {{ $record->workorder?->disposalWorkorder?->quantity }}
                  @else
                    {{ $record->workorder->request?->quantity }}
                  @endif
                </x-td> 
                <x-td>{{ $record->workorder->completedBy?->name }}</x-td>
              </tr>
            @endforeach
          </tbody>
         </x-tables>
        @else
          {{-- Timeline placeholder --}}
          <div class="text-center py-8">
            <x-heroicon-o-clock class="size-16 text-gray-300 mx-auto mb-3"/>
            <p class="text-gray-500">No history recorded yet</p>
            <p class="text-sm text-gray-400 mt-1">Asset activities will appear here</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@include('modals.assets-modals.dispose-asset-modal')
@endsection

@section('scripts')
  @vite('resources/js/assets/dispose-asset/disposeAsset.js')
@endsection