<x-modal name="completeWorkorder" title="Complete Workorder">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure you want to mark this workorder as complete?</p>
      <p class="text-sm text-gray-600">This action cannot be undone and will update the asset status.</p>
    </div>
    <form method="POST" id="completeForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>