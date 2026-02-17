<x-modal name="deleteDepartment" title="Delete Department">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure to cancel this department?</p>
      <p class="text-sm text-gray-600">This action cannot be undone.</p>
    </div>
    <form method="POST" id = "deleteForm">
      @csrf
      @method("DELETE")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>