@extends('layouts.pageslayout')
@section('content')
<x-pages-header title="Departments" description="View and manage departments">
  <x-heroicon-s-briefcase class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

{{-- show success toast! --}}
<x-toast-success />
{{-- show session errors! --}}
<x-session-error />

<div class = "md:m-4">
  {{-- show the errors! --}}
  <x-validation-error />
  
  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">

      <x-search-bar route="department.index" placeholder="Search departments..."/>
      
      @can('manage departments')
        <x-buttons class="w-full sm:w-auto" onclick="createDepartment.showModal()">
          <x-heroicon-s-plus class="size-5"/>
          Create Department
        </x-buttons>
      @endcan
    </div>
    <x-tables :columnNames="$columns" :centeredColumns="[3]">
      <tbody class = "divide-y divide-gray-400">
          @foreach($departments as $department)
            <tr>
              <th class = "p-3 text-center">{{ $department -> id }}</th>
              <x-td>{{ $department->name}}</x-td>
              <x-td>{{ $department->description}}</x-td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @can("manage departments")
                  <x-buttons onclick="editDepartment.showModal()"
                    class="editButton tooltip tooltip-top"
                    data-tip="Edit"
                    aria-label="Edit Department"
                    data-route="{{ route('departments.update', $department->id ) }}"
                    data-name="{{ $department -> name}}"
                    data-description="{{ $department -> description}}">
                    <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                  </x-buttons>
                  <x-buttons onclick="deleteDepartment.showModal()"
                      class="deleteButton bg-red-700 tooltip tooltip-top"
                      data-tip="Delete"
                      aria-label="Delete Department"
                      data-route="{{ route('departments.delete', $department->id ) }}">
                    <x-heroicon-s-trash class="size-4 sm:size-5"/>
                  </x-buttons>
                @endcan
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $departments->links() }}
    </div>
  </div>
</div>

@include('modals.department-modals.create-department-modal')
@include('modals.department-modals.delete-department-modal')
@include('modals.department-modals.edit-department-modal')
@endsection

@section('scripts')
  @vite('resources/js/department/delete-department.js')
  @vite('resources/js/department/edit-department.js')
@endsection