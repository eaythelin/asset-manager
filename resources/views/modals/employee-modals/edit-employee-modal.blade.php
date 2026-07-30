<x-modal name="editEmployee" title="Edit Employee">
  <form method = "POST" id="editForm">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      @method('PUT')
      <label class="font-medium" for="edit_emp_no">Employee No.</label>
      <x-modal-input-box id="edit_emp_no" name="employee_no" readonly/>
      <x-label for="edit_name" :required="true">Name</x-label>
      <x-modal-input-box id="edit_name" name="name" autocomplete="given_name"/>
      <x-label for="edit_selectDepartment" :required="true">Department </x-label>
      <select name = 'department' id="edit_selectDepartment" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Department--</option>
        @foreach($departments as $id => $department_name)
          <option value="{{ $id }}">{{ $department_name }}</option>
        @endforeach
      </select>
      <x-buttons class="mt-2" type="Submit">Save Changes</x-buttons>
    </div>
  </form>
</x-modal>
