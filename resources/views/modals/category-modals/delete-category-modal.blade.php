<x-modal name="deleteCategory" title="Delete Category">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <h2 class = "text-base">This will permanently delete the category!</h2>
    <form method="POST" id = "deleteForm">
      @csrf
      @method("DELETE")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>