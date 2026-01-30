<x-modal name="createCategory" title="Create Category">
  <form method = "POST" action = "{{ route('category.store') }}">
      <div class = "flex flex-col gap-3 px-2 sm:px-4">
        @csrf
        <x-label for="create_category_name" :required="true">Category Name </x-label>
        <x-modal-input-box id="create_category_name" name="name" autocomplete="off"/>
        <x-label for="create_description">Description </x-label>
        <x-modal-text-area-box id="create_description" name="description"/>
        <x-buttons class="mt-2" type="submit">Submit</x-buttons>
      </div>
    </form>
</x-modal>