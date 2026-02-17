<x-modal name="editSubCategory" title="Edit Subcategory">
  <form method = "POST" id = "editForm">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      @method('PUT')
      <x-label for="edit_name" :required="true">Name </x-label>
      <x-modal-input-box id="edit_name" name="name" autocomplete="off"/>
      <x-label for="edit_selectCategory" :required="true">Category </x-label>
      <select name = 'category_id' id="edit_selectCategory" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Category--</option>
        @foreach($categories as $id => $name)
          <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
      <x-label for="edit_description">Description </x-label>
      <x-modal-text-area-box id="edit_description" name="description" />
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>