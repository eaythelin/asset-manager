<x-modal name="createEmployee" title="Create Employee" x-data="{selectedDept: ''}">
  <form method = "POST" action="{{ route('employees.store') }}">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      <x-label for="create_emp_no" :required="true">Employee No.<span class="text-xs text-gray-500 align-super tooltip tooltip-info" data-tip="Must be unique">?</span></x-label>
      <x-modal-input-box id="create_emp_no" name="employee_no" placeholder="EMP-0001" value="{{ $employeeNo }}"/>
      <x-label for="create_name" :required="true">Name </x-label>
      <x-modal-input-box id="create_name" name="name" autocomplete="given-name"/>
      <x-label for="create_selectDepartment" :required="true">Department </x-label>
      <select name = 'department' id="create_selectDepartment" x-model="selectedDept" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Department--</option>
        @foreach($departments as $id => $department_name)
          <option value="{{ $id }}">{{ $department_name }}</option>
        @endforeach
      </select>
      <div class="flex flex-row gap-3">
        <label class="font-medium" for="is_maintenance">Maintenance Crew <span class="text-xs text-gray-500 align-super tooltip tooltip-info" data-tip="Marks employee as maintenance crew">?</span></label>
        <input type="checkbox" class="checkbox checkbox-primary" name="is_maintenance" id="is_maintenance">
      </div>
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>
