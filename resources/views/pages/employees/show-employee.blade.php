@extends('layouts.pageslayout')
@section('content')

<div class = "md:mx-4">
  <div class = "mb-4">
    <x-back-link route="employees.index">Return to Employees</x-back-link>
  </div>

  <div class = "bg-white p-4 rounded-2xl shadow-xl mb-4">
    <div class = "flex flex-col sm:flex-row items-center sm:justify-between px-2 gap-5">
      <div class="flex flex-col gap-2">
        <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $employee->full_name }}</p>
        <div class="flex items-center gap-2 text-sm">
            <x-heroicon-s-briefcase class="text-gray-500 size-4"/>
            <span class="text-gray-500">Department:</span>
            <span class="font-semibold text-gray-700">{{ $employee->department->name }}</span>
        </div>
      </div>
      <div class="flex items-center gap-2">
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
      </div>
    </div>
  </div>

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
              @if($asset->computed_status === "expired")
                <span class = "badge badge-warning text-white font-medium text-sm p-3 tooltip tooltip-top"
                  data-tip="Asset has reach the end of its lifecycle">Expired</span>
              @elseif($asset->computed_status === "disposed")
                <span class = "badge badge-error text-white font-medium text-sm">Disposed</span>
              @elseif($asset->computed_status === "under_service")
                <span class = "badge badge-info text-white font-medium text-sm">Under Service</span>
              @elseif($asset->computed_status === "active")
                <span class = "badge badge-success text-white font-medium text-sm">Active</span>
              @endif
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