@extends("layouts.pageslayout")
@section("content")
<x-pages-header title="Users" description="View and manage system users">
  <x-heroicon-s-user class="text-blue-800 size-8 md:size-10"/>
</x-pages-header>

<x-toast-success />
<x-session-error />

<div class = "md:m-4">
  {{-- show the errors! --}}
  <x-validation-error />

  <div class = "bg-white p-4 rounded-2xl shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4 mx-2">
      <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <x-search-bar route="users.index" placeholder="Search users..."/>
    
        @can('manage users')
          <form method="GET" action="{{ route('users.index') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <label class="flex mt-3 items-center gap-2 cursor-pointer">
              <input 
                type="checkbox" 
                name="show_deleted" 
                class="checkbox checkbox-sm"
                {{ request('show_deleted') ? 'checked' : '' }}
                onchange="this.form.submit()"
              />
              <span class="text-sm font-medium">Show deleted</span>
            </label>
          </form>
        @endcan
      </div>

      @can('manage users')
        <x-buttons class="w-full sm:w-auto" onclick="createUser.showModal()">
          <x-heroicon-s-plus class="size-5"/>
          Create User
        </x-buttons>
      @endcan
</div>
    <x-tables :columnNames="$columns" :centeredColumns="[5]">
      <tbody class = "divide-y divide-gray-400">
          @foreach($users as $user)
            <tr>
              <th class = "p-3 text-center">{{ $user -> id }}</th>
              <x-td>{{ $user->name}}</x-td>
              <x-td>{{ $user->email}}</x-td>
              <td class = "p-3">
                @if($user -> is_active)
                  <span class = "badge badge-success text-white font-medium">Active</span>
                @else
                  <span class = "badge badge-error text-white font-medium">Disabled</span>
                @endif
              </td>
              <td class = 'p-3'>{{ $user -> getRoleNames() -> first() }}</td>
              <td class = "flex flex-row gap-2 sm:gap-4 justify-center">
                @if($user->trashed())
                <div class = "flex flex-col sm:flex-row gap-2">
                  <x-buttons onclick="reactivateUser.showModal()"
                    class="restoreButton"
                    data-route="{{ route('users.restore', $user->id ) }}">
                    <x-heroicon-o-arrow-uturn-left class="size-4 sm:size-5"/>
                    Reactivate
                  </x-buttons>
                  <x-buttons onclick="permaDeleteUser.showModal()"
                    class="permaDeleteBtn bg-red-700"
                    data-route="{{ route('users.force-delete', $user->id ) }}">
                    <x-heroicon-s-trash class="size-4 sm:size-5"/>
                    Permanent Delete
                  </x-buttons>
                </div>
                @else
                  @can("manage users")
                    <x-buttons onclick="editUser.showModal()"
                      class="editButton tooltip tooltip-top"
                      data-tip="Edit"
                      aria-label="Edit User"
                      data-user="{{ json_encode([
                        'employee_id'=> $user->employee_id,
                        'employee_name'=> $user->employee->first_name . ' ' . $user->employee->last_name,
                        'role_id'=> $user->roles->first()->id ?? null,
                        'email'=> $user->email,
                        'route'=> route('users.update', $user->id )]) }}">
                      <x-heroicon-o-pencil-square class="size-4 sm:size-5" />
                    </x-buttons>
                    <div class = "flex justify-center items-center">
                      <form method="POST" action="{{ route('users.toggle', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <x-buttons class="tooltip" data-tip="{{ $user -> is_active ? 'Deactivate user' : 'Re-enable user' }}" type="submit">
                          <x-heroicon-c-power class="size-4 sm:size-5"/>
                        </x-buttons>
                      </form>
                    </div>
                    <x-buttons onclick="deleteUser.showModal()"
                      class="deleteButton tooltip tooltip-top bg-red-700"
                      data-tip="Delete"
                      aria-label="Delete User"
                      data-route="{{ route('users.soft-delete', $user->id ) }}">
                      <x-heroicon-s-trash class="size-4 sm:size-5"/>
                    </x-buttons>
                  @endcan
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
    </x-tables>
    <div class = "text-base-content">
      {{ $users->links() }}
    </div>
  </div>
</div>

@include('modals.user-modals.create-user-modal', [$employees, $roles])
@include('modals.user-modals.delete-user-modal')
@include('modals.user-modals.edit-user-modal', [$employees, $roles])
@include('modals.user-modals.reactivate-user-modal')
@include('modals.user-modals.permanent-delete-user');
@endsection

@section('scripts')
  @vite('resources/js/user/soft-delete-user.js')
  @vite('resources/js/user/edit-user.js')
  @vite('resources/js/user/restore-user.js')
  @vite('resources/js/user/force-delete-user.js')
@endsection