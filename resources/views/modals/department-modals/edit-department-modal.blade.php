<x-modal name="editDepartment" title="Edit Department">
  <form id="editForm" method = "POST">
      <div class = "flex flex-col gap-3 px-2 sm:px-4">
        @csrf
        @method("PUT")
        <x-label for="edit_department_name" :required="true">Department Name </x-label>
        <x-modal-input-box id="edit_department_name" name="name" autocomplete="off"/>
        <x-label for="edit_description">Description </x-label>
        <x-modal-text-area-box id="edit_description" name="description"/>
        <x-buttons class="mt-2" type="submit">Submit</x-buttons>
      </div>
    </form>
</x-modal>