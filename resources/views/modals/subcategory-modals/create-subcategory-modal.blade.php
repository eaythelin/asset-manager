<x-modal name="createSubCategory" title="Create Subcategory" x-data="{selectedCategory: ''}">
  <form method = "POST" action="{{ route('subcategory.store') }}">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      <x-label for="create_name" :required="true">Name </x-label>
      <x-modal-input-box id="create_name" name="name" autocomplete="off"/>
      <x-label for="create_selectCategory" :required="true">Category </x-label>
      <select name = 'category_id' id="create_selectCategory" x-model="selectedCategory" class="select w-full rounded-xl">
        <option value="" disabled selected>--Select Category--</option>
        @foreach($categories as $id => $name)
          <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
      <x-label for="create_description" :required="true">Description </x-label>
      <x-modal-text-area-box id="create_description" name="description" />
      <x-buttons class="mt-2" type="Submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>