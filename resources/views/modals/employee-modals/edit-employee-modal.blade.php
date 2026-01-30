<x-modal name="editEmployee" title="Edit Employee">
  <form method = "POST" id="editForm">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      @method('PUT')
      <x-label for="edit_first_name" :required="true">First Name </x-label>
      <x-modal-input-box id="edit_first_name" name="first_name" autocomplete="given_name"/>
      <x-label for="edit_last_name" :required="true">Last Name </x-label>
      <x-modal-input-box id="edit_last_name" name="last_name" />
      <x-label for="edit_selectDepartment" :required="true">Department </x-label>
      <select name = 'department_id' id="edit_selectDepartment" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Department--</option>
        @foreach($departments as $id => $department_name)
          <option value="{{ $id }}">{{ $department_name }}</option>
        @endforeach
      </select>
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>