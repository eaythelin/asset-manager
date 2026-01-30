<x-modal :name="'createUser'" title="Create User" x-data="{selectedEmployee: '', selectedRole: ''}">
  <form method = "POST" action="{{ route('users.store') }}">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      <x-label :for="'create_selectEmployee'" :required="true">Employee Link </x-label>
      <select name = 'employee_id' id="create_selectEmployee" x-model="selectedEmployee" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Employee--</option>
        @foreach($employees as $id => $full_name)
          <option value="{{ $id }}">{{ $full_name }}</option>
        @endforeach
      </select>
      <x-label :for="'create_selectRole'" :required="true">Role </x-label>
      <select name = 'role_id' id="create_selectRole" x-model="selectedRole" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Role--</option>
        @foreach($roles as $id => $role_name)
          <option value="{{ $id }}">{{ $role_name }}</option>
        @endforeach
      </select>
      <x-label for="create_email" :required="true">Email </x-label>
      <x-modal-input-box id="create_email" :name="'email'" type="email" autocomplete="email"/>
      <x-label :for="'create_password'" :required="true">Password </x-label>
      <x-modal-input-box :id="'create_password'" :name="'password'" type="password" />
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>