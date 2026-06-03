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
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Control Number</span>
            <span class="text-xs py-0.5 px-2 bg-gray-100 text-gray-600 rounded-md font-mono">
              #{{ $requestModel->control_number }}
            </span>
          </div>
          <h2 class="text-xl font-bold text-gray-900 leading-tight">
            {{ $requestModel->request_type->label() }}
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

  <div class="bg-white p-6 rounded-2xl shadow-xl">
    <div class="flex flex-row items-center gap-2 mb-2">
      <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
      <p class="text-lg font-semibold">Repair and Maintenance Request Form</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="space-y-5">
        <x-detail-item label="Requisitioner" :value="$requestModel->requestedBy->name"/>
        <x-detail-item label="Equipment/Vehicle" :value="$requestModel->asset->asset_code . ' - ' . $requestModel->asset->name . ($requestModel->asset->serial_name ? ' (' . $requestModel->asset->serial_name . ')' : '')"/>
        <x-detail-item label="Description/Plate No." :value="$requestModel->description"/>
      </div>

      <div class="space-y-5">
        <x-detail-item label="Department" :value="$requestModel->department->name"/>
        <x-detail-item label="Date" :value="$requestModel->created_at->format('M d, Y')"/>
      </div>

      <div class="space-y-5">
        <x-detail-item label="Approved By" :value="$requestModel->approved_by->name ?? 'N/A'"/>
        <x-detail-item label="Approved At" :value="$requestModel->approved_at?->format('M d, Y') ?? 'N/A'"/>
      </div>
    </div>
  </div>
</div>
@endsection
