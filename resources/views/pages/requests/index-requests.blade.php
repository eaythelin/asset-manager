@extends('layouts.pageslayout')
@section('content')
<x-pages-header title="Requests" :description="$desc">
  <x-heroicon-s-clipboard-document-list class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="requests.index" placeholder="Search requests..."/>
      
      @can('create requests')
      <a href = "{{ route('requests.create') }}" class="w-full sm:w-auto">
        <x-buttons class="w-full sm:w-auto">
          <x-heroicon-s-plus class="size-5"/>
          Create New Request
        </x-buttons>
      </a>
      @endcan
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="$centeredColumns">
      <tbody class = "divide-y divide-gray-400">
          @foreach($requests as $request)
            <tr>
              <th class = "p-3 text-center">{{ $request->request_code }}</th>
              @if(in_array(auth()->user()->getRoleNames()->first(), ['General Manager', 'System Supervisor']))
                <x-td>{{ $request->requestedBy->name }}</x-td>
              @endif

              @if($request->type->value === "requisition")
                <x-td>{{ $request->asset_name }}</x-td>
              @else
                <x-td>{{ $request->asset?->name }}</x-td>
              @endif

              <x-td>{{ $request->type->label() }}</x-td>
              @if($request->type->value === "requisition")
                <x-td>{{ $request->category->name }}</x-td>
              @else
                <x-td>{{ $request->asset->category?->name }}</x-td>
              @endif

              <x-td>{{ $request->date_requested->format('M d, Y') }}</x-td>
              <x-td class="text-center">
                <span class="badge {{ $request->status->badgeClass() }} text-white font-medium text-sm">
                  {{ $request->status->label() }}
                </span>
              </x-td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @if($request->status->value != "draft")
                  <x-buttons
                    class="viewBtn tooltip tooltip-top"
                    data-tip="View"
                    aria-label="View Request">
                    <x-heroicon-s-eye class="size-4 sm:size-5" />
                  </x-buttons>
                @endif
                @if(auth()->user()->can('create requests') && $request->status->value === "draft")
                  <x-buttons onclick="submitRequest.showModal()"
                    class="submitBtn tooltip tooltip-top"
                    data-tip="Submit"
                    aria-label="Submit Request"
                    data-route="{{ route('requests.submit', $request->id )}}">
                    <x-heroicon-s-paper-airplane class="size-4 sm:size-5"/>
                  </x-buttons>
                  <x-buttons
                    class="editBtn tooltip tooltip-top"
                    data-tip="Edit"
                    aria-label="Edit Request">
                    <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                  </x-buttons>
                  <x-buttons onclick="cancelRequest.showModal()"
                    class="cancelBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Cancel"
                    aria-label="Cancel Request"
                    data-route="{{ route('requests.cancel', $request->id )}}">
                    <x-heroicon-c-x-mark class="size-4 sm:size-6"/>
                  </x-buttons>
                @endif
                @if(auth()->user()->can('approve requests') && $request->status->value === "pending")
                  <x-buttons onclick="approveRequest.showModal()"
                    class="approveBtn bg-green-700 tooltip tooltip-top"
                    data-tip="Approve"
                    aria-label="Approve Request"
                    data-route="{{ route('requests.approve', $request->id )}}">
                    <x-heroicon-m-check class="size-4 sm:size-5"/>
                  </x-buttons>
                @endif
                @if(auth()->user()->can('decline requests') && $request->status->value === "pending")
                  <x-buttons onclick="declineRequest.showModal()"
                    class="declineBtn bg-red-700 tooltip tooltip-top"
                    data-tip="Decline"
                    aria-label="Decline Request"
                    data-route="{{ route('requests.decline', $request->id )}}">
                    <x-heroicon-o-x-mark class="size-4 sm:size-5"/>
                  </x-buttons>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $requests->links() }}
    </div>
  </div>
</div>

@include('modals.requests-modals.submit-request-modal')
@include('modals.requests-modals.cancel-request-modal')
@include('modals.requests-modals.approve-request-modal')
@include('modals.requests-modals.decline-request-modal')

@endsection

@section('scripts')
  @vite('resources/js/requests/submitRequest.js')
  @vite('resources/js/requests/softDeleteRequest.js')
  @vite('resources/js/requests/approveRequest.js')
  @vite('resources/js/requests/declineRequest.js')
@endsection