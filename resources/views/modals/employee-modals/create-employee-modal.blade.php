<x-modal name="createEmployee" title="Create Employee" x-data="{selectedDept: ''}">
  <form method = "POST" action="{{ route('employees.store') }}">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      <x-label for="create_first_name" :required="true">First Name </x-label>
      <x-modal-input-box id="create_first_name" name="first_name" autocomplete="given-name"/>
      <x-label for="create_last_name" :required="true">Last Name </x-label>
      <x-modal-input-box id="create_last_name" name="last_name" />
      <x-label for="create_selectDepartment" :required="true">Department </x-label>
      <select name = 'department_id' id="create_selectDepartment" x-model="selectedDept" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Department--</option>
        @foreach($departments as $id => $department_name)
          <option value="{{ $id }}">{{ $department_name }}</option>
        @endforeach
      </select>
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>