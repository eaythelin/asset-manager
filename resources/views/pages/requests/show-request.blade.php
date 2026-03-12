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
              <span class="text-xs py-0.5 px-2 bg-gray-100 text-gray-600 rounded-md font-mono">
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
      @if($requestModel->type->value === "requisition")
        <div class="bg-white p-4 rounded-2xl shadow-xl">
          <div class="flex flex-row items-center gap-2 mb-2">
            <x-heroicon-s-document-text class="size-6 text-green-700"/>
            <p class="text-lg font-semibold">Request Details</p>
          </div>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-information-circle class="size-6 text-blue-700"/>
              <p class="text-lg font-semibold">Asset Details</p>
            </div>
          </div>

          <div class="bg-white p-4 rounded-2xl shadow-xl">
            <div class="flex flex-row items-center gap-2 mb-2">
              <x-heroicon-s-document-text class="size-6 text-green-700"/>
              <p class="text-lg font-semibold">Request Details</p>
            </div>
            <div class="space-y-3">
              <div>
                @if($requestModel->type->value === "disposal")
                  <p class="text-sm text-gray-500">Condition</p>
                  <p class="font-semibold text-gray-700">{{ $requestModel->condition?->label() }}</p>
                @elseif($requestModel->type->value === "service")
                  <p class="text-sm text-gray-500">Service Type</p>
                  <p class="font-semibold text-gray-700">{{ $requestModel->service_type?->label() }}</p>
                @endif
              </div>
              <div>
                <p class="text-sm text-gray-500">Description</p>
                <p class="font-semibold text-gray-700">{{ $requestModel->description ?? 'N/A'}}</p>
              </div>
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
          <p>potato</p>
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