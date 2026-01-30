<x-modal :name="'editUser'" title="Edit User">
  <form method = "POST" id="editForm">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      @method('PUT')
      <x-label :for="'edit_selectEmployee'" :required="true">Employee Link </x-label>
      <select name = 'employee_id' id="edit_selectEmployee" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Employee--</option>
        @foreach($employees as $id => $full_name)
          <option value="{{ $id }}">{{ $full_name }}</option>
        @endforeach
      </select>
      <x-label :for="'edit_selectRole'" :required="true">Role </x-label>
      <select name = 'role_id' id="edit_selectRole" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Role--</option>
        @foreach($roles as $id => $role_name)
          <option value="{{ $id }}">{{ $role_name }}</option>
        @endforeach
      </select>
      <x-label :for="'edit_email'" :required="true">Email </x-label>
      <x-modal-input-box :id="'edit_email'" :name="'email'" type="email" autocomplete="email"/>
      <hr class="border-gray-300 mt-2">
      <p class="text-xs sm:text-sm text-gray-500 italic mb-3 text-center">Leave password fields blank to keep current password</p>
      <x-label :for="'edit_password'">Password </x-label>
      <x-modal-input-box :id="'edit_password'" :name="'password'" type="password"  />
      <x-label :for="'confirm_password'">Confirm Password </x-label>
      <x-modal-input-box :id="'confirm_password'" :name="'password_confirmation'" type="password"/>
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>