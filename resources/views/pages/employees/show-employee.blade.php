@extends('layouts.pageslayout')
@section('content')

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="employees.index">Return to Employees</x-back-link>
  </div>

  <x-view-page-header-card>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div class="flex items-start gap-4">
        <div class="hidden sm:flex items-center justify-center size-12 rounded-xl bg-indigo-50 text-indigo-600">
          <x-heroicon-s-user class="size-6"/>
        </div>

        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Department: </span>
            <span class="text-xs py-0.5 px-2 bg-gray-100 text-gray-600 rounded-md font-mono">
              {{ $employee->department->name }}
            </span>
          </div>
          <h2 class="text-xl font-bold text-gray-900 leading-tight">
            {{ $employee->full_name }}
          </h2>
        </div>
      </div>

      <div class="flex flex-col items-end gap-2">
        @if($employee->assets->count() > 0)
          <span class="badge badge-success p-4 font-medium text-sm flex items-center gap-1">
            <x-heroicon-m-check class="size-5" />
            Asset Custodian
          </span>
        @else
          <span class="badge badge-error p-4 font-medium text-sm flex items-center gap-1 text-white">
            <x-heroicon-c-x-mark class="size-5" />
            No Asset Assigned
          </span>
        @endif
        <span class="text-xs text-gray-400 flex items-center gap-1">
          <x-heroicon-c-clock class="size-3"/>
          Updated {{ $employee->updated_at->diffForHumans() }}
        </span>
      </div>
    </div>
  </x-view-page-header-card>

  <div class="bg-white p-6 rounded-2xl shadow-xl">
    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Assigned Assets</h3>
    @if($employee->assets->count() > 0)
    <!-- if assets assigned -->
      <x-tables :columnNames="$columns" :centeredColumns="[0,6]">
        <tbody class = "divide-y divide-gray-400">
          @foreach($employee->assets as $asset)
          <tr>
            <th class = "p-3 text-center">{{ $asset->asset_code}}</th>
            <x-td>{{ $asset->name}}</x-td>
            <x-td>{{ $asset->serial_name}}</x-td>
            <x-td>{{ $asset->department->name}}</x-td>
            <x-td>{{ $asset->category->name }}</x-td>
            <x-td>{{ $asset->subCategory?->name }}</x-td>
            <x-td class="text-center">
              <span class="badge {{ $asset->status->badgeColor() }} text-white font-medium text-sm p-3">
                {{ $asset->status->label() }}
              </span>
            </x-td>
          </tr>
          @endforeach
        </tbody>
      </x-tables>
    @else
    <!-- if no assets assigned -->
      <div class="flex flex-col items-center justify-center py-12 text-center">
        <x-heroicon-s-archive-box class="size-16 text-gray-300 mb-4" />
        <p class="text-gray-600 text-lg">No assets assigned</p>
        <p class="text-gray-400 text-sm mt-2">Assets will appear here once they are assigned to this employee</p>
      </div>
    @endif
  </div>
</div>
@endsection