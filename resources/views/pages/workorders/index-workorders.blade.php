@extends('layouts.pageslayout')
@section('content')

<x-pages-header title="Workorders" description="View and manage workorders">
  <x-heroicon-s-clipboard-document class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="workorders.index" placeholder="Search workorders..."/>
      
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="[0,1,3,6,7]">
      <tbody class = "divide-y divide-gray-400">
          @foreach($workorders as $workorder)
            <tr>
              <th class = "p-3 text-center">{{ $workorder->workorder_code }}</th>
              <x-td class="text-center">{{ $workorder->request?->request_code}}</x-td>
              <x-td>{{ $workorder->workorder_type->label()}}</x-td>
              <x-td class="text-center">
                <span class="badge {{ $workorder->priority_level->badgeClass() }} text-white font-medium text-sm">
                  {{ $workorder->priority_level->label() }}
                </span>
              </x-td>
              <x-td>{{ $workorder->start_date?->format('M d, Y') }}</x-td>
              <x-td>{{ $workorder->end_date?->format('M d, Y') }}</x-td>
              <x-td class="text-center">
                <span class="badge {{ $workorder->status->badgeClass() }} text-white font-medium text-sm">
                  {{ $workorder->status->label() }}
                </span>
              </x-td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @can("manage workorders")
                  <a class="w-full sm:w-auto flex justify-center" href="{{ route('workorders.view', $workorder->id) }}">
                    <x-buttons
                      class="viewBtn tooltip tooltip-top"
                      data-tip="View"
                      aria-label="View Workorder">
                      <x-heroicon-s-eye class="size-4 sm:size-5" />
                    </x-buttons>
                  </a>
                  @if($workorder->status->value === "pending")
                    <x-buttons onclick="startWorkorder.showModal()"
                      class="startBtn bg-green-700 tooltip tooltip-top"
                      data-tip="Start"
                      aria-label="Start Workorder"
                      data-route="{{ route('workorders.start', $workorder->id ) }}">
                       <x-heroicon-s-play class="size-4 sm:size-5"/>
                    </x-buttons>
                  @endif
                  @if(in_array($workorder->status->value, ['in_progress', 'overdue']))
                    <x-buttons onclick="completeWorkorder.showModal()"
                      class="completeBtn bg-green-700 tooltip tooltip-top"
                      data-tip="Complete"
                      aria-label="Complete Workorder"
                      data-route="{{ route('workorders.complete', $workorder->id ) }}">
                    <x-heroicon-s-check class="size-4 sm:size-5"/>
                  </x-buttons>
                  @endif
                  @if(!in_array($workorder->status->value, ['completed', 'cancelled']))
                    <a class="w-full sm:w-auto flex justify-center" href="{{ route('workorders.edit', $workorder->id) }}">
                      <x-buttons
                        class="editBtn tooltip tooltip-top"
                        data-tip="Edit"
                        aria-label="Edit Workorder">
                        <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                      </x-buttons>
                    </a>
                    <x-buttons onclick="cancelWorkorder.showModal()"
                      class="cancelBtn bg-red-700 tooltip tooltip-top"
                      data-tip="Cancel"
                      aria-label="Cancel Workorder"
                      data-route="{{ route('workorders.cancel', $workorder->id ) }}">
                      <x-heroicon-s-x-mark class="size-4 sm:size-5"/>
                    </x-buttons>
                  @endif
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $workorders->links() }}
    </div>
  </div>
</div>

@include('modals.workorder-modals.start-workrorder-modal')
@include('modals.workorder-modals.complete-workorder-modal')
@include('modals.workorder-modals.cancel-workorder-modal')

@endsection

@section('scripts')
  @vite('resources/js/workorder/startWorkorder.js')
  @vite('resources/js/workorder/completeWorkorder.js')
  @vite('resources/js/workorder/cancelWorkorder.js')
@endsection