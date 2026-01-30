<x-modal name="editCategory" title="Edit Category">
  <form method = "POST" id="editForm">
      <div class = "flex flex-col gap-3 px-2 sm:px-4">
        @csrf
        @method('PUT')
        <x-label for="edit_category_name" :required="true">Category Name </x-label>
        <x-modal-input-box id="edit_category_name" name="name" autocomplete="off" />
        <x-label for="edit_description">Description </x-label>
        <x-modal-text-area-box id="edit_description" name="description"/>
        <x-buttons class="mt-2" type="submit">Submit</x-buttons>
      </div>
    </form>
</x-modal>