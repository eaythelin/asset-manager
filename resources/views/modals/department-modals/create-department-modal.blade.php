<x-modal name="createDepartment" title="Create Department">
  <form method = "POST" action = "{{ route('departments.store') }}">
      <div class = "flex flex-col gap-3 px-2 sm:px-4">
        @csrf
        <x-label for="create_department_name" :required="true">Department Name </x-label>
        <x-modal-input-box id="create_department_name" name="name" autocomplete="off"/>
        <x-label for="create_description">Description </x-label>
        <x-modal-text-area-box id="create_description" name="description"/>
        <x-buttons class="mt-2" type="submit">Submit</x-buttons>
      </div>
    </form>
</x-modal>