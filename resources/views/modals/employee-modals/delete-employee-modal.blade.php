<x-modal name="deleteEmployee" title="Delete Employee">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium deleteQuestion">Are you sure to submit this request?</p>
      <p class="text-sm text-gray-600 deleteWarning">Once submitted, this request cannot be edited.</p>
    </div>
    <form method="POST" id = "deleteForm">
      @csrf
      @method("DELETE")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>