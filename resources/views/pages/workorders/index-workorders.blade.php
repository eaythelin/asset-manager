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
              <x-td class="text-center">{{ $workorder->request->request_code}}</x-td>
              <x-td>{{ $workorder->type->label()}}</x-td>
              <x-td class="text-center">
                <span class="badge {{ $workorder->priority_level->badgeClass() }} text-white font-medium text-sm">
                  {{ $workorder->priority_level->label() }}
                </span>
              </x-td>
              <x-td>{{ $workorder->start_date->format('M d, Y') }}</x-td>
              <x-td>{{ $workorder->end_date->format('M d, Y') }}</x-td>
              <x-td class="text-center">
                <span class="badge {{ $workorder->status->badgeClass() }} text-white font-medium text-sm">
                  {{ $workorder->status->label() }}
                </span>
              </x-td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @can("manage workorders")
                  @if(in_array($workorder->status->value, ['completed', 'cancelled']))
                    <x-buttons
                      class="viewBtn tooltip tooltip-top"
                      data-tip="View"
                      aria-label="View Workorder">
                      <x-heroicon-s-eye class="size-4 sm:size-5" />
                    </x-buttons>
                  @endif
                  @if($workorder->status->value === "pending")
                    <x-buttons
                      class="startBtn bg-green-700 tooltip tooltip-top"
                      data-tip="Start"
                      aria-label="Start Workorder">
                       <x-heroicon-s-play class="size-4 sm:size-5"/>
                    </x-buttons>
                  @endif
                  @if(in_array($workorder->status->value, ['in_progress', 'overdue']))
                    <x-buttons
                      class="completeBtn bg-green-700 tooltip tooltip-top"
                      data-tip="Complete"
                      aria-label="Complete Workorder">
                    <x-heroicon-s-check class="size-4 sm:size-5"/>
                  </x-buttons>
                  @endif
                  @if(!in_array($workorder->status->value, ['completed', 'cancelled']))
                    <x-buttons
                      class="editBtn tooltip tooltip-top"
                      data-tip="Edit"
                      aria-label="Edit Workorder">
                      <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                    </x-buttons>
                    <x-buttons
                      class="cancelBtn bg-red-700 tooltip tooltip-top"
                      data-tip="Cancel"
                      aria-label="Cancel Workorder">
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

@endsection