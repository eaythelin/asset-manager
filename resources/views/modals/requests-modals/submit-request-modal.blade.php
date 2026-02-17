<x-modal name="submitRequest" title="Submit Request">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure to submit this request?</p>
      <p class="text-sm text-gray-600">Once submitted, this request cannot be edited.</p>
    </div>
    <form method="POST" id = "submitForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>