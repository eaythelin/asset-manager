@extends("layouts.pageslayout")
@section("content")

<x-pages-header title="Assets" :description="$desc">
  <x-heroicon-s-user-group class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
      <x-search-bar route="assets.index" placeholder="Search assets..."/>

      <form method="GET" action="{{ route('assets.index') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <label class="flex items-center gap-2 cursor-pointer whitespace-nowrap">
          <input 
            type="checkbox" 
            name="show_deleted" 
            class="checkbox checkbox-sm"
            {{ request('show_deleted') ? 'checked' : '' }}
            onchange="this.form.submit()"
          />
          <span class="text-sm font-medium">Show Disposed</span>
        </label>
      </form>
    </div>

    @can('manage assets')
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn flex flex-row gap-2 bg-green-700 text-white rounded-lg font-bold hover:bg-green-600/30 hover:text-green-800 active:bg-green-600 active:text-white">
          <x-heroicon-o-document-arrow-down class="size-5"/>
          Import from Excel
        </div>
        <ul tabindex="-1" class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
          <li><a class="text-sm" href="{{ route('assets.template') }}">
              Download Template</a>
          </li>
          <li><a class="text-sm">Import</a></li>
        </ul>
      </div>
      <a href="{{ route('assets.create') }}" class="w-full sm:w-auto">
        <x-buttons class="w-full">
          <x-heroicon-s-plus class="size-5"/>
          Create Asset
        </x-buttons>
      </a>
    </div>
    @endcan

  </div>
    <x-tables :columnNames="$columns" :centeredColumns="[0,7,8]">
      <tbody class = "divide-y divide-gray-400">
          @foreach($assets as $asset)
            <tr>
              <th class = "p-3 text-center">{{ $asset->asset_code }}</th>
              <x-td>{{ $asset->name}}</x-td>
              <x-td>{{ $asset->quantity}}</x-td>
              <x-td>{{ $asset->serial_name}}</x-td>
              <x-td>{{ $asset->department->name}}</x-td>
              <x-td>{{ $asset->custodian?->first_name}} {{ $asset->custodian?->last_name}}</x-td>
              <x-td>{{ $asset->category->name}}</x-td>
              <x-td class="text-center">
                <span class="badge {{ $asset->status->badgeColor() }} text-white font-medium text-sm p-3">
                  {{ $asset->status->label() }}
                </span>
              </x-td>
              <td class = "flex flex-row gap-2 sm:gap-3 justify-center">
                @can("view assets")
                  <a href="{{ route('assets.show', $asset->id) }}" class="w-full sm:w-auto flex justify-center">
                    <x-buttons class="px-4 tooltip tooltip-top" data-tip="View Asset" aria-label="View Asset Information">
                      <x-heroicon-s-eye class="size-4 sm:size-5"/>
                    </x-buttons>
                  </a>
                @endcan
                @if(auth()->user()->can('manage assets') && $asset->status->value !== "disposed")
                  <a href="{{ route('assets.edit', $asset->id) }}" class="w-full sm:w-auto flex justify-center">
                    <x-buttons
                      class="editBtn tooltip tooltip-top"
                      data-tip="Edit"
                      aria-label="Edit Asset">
                      <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                    </x-buttons>
                  </a>
                  <x-buttons onclick="disposeAsset.showModal()"
                    class="disposeBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Dispose"
                    aria-label="Dispose Asset"
                    data-route="{{ route('assets.dispose', $asset->id) }}">
                    <x-heroicon-s-archive-box class="size-4 sm:size-5"/>
                  </x-buttons>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $assets->links() }}
    </div>
  </div>
</div>

@include('modals.assets-modals.dispose-asset-modal')
@endsection

@section('scripts')
  @vite('resources/js/assets/dispose-asset/disposeAsset.js')
@endsection