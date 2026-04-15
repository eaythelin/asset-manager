<x-modal name="cancelWorkorder" title="Cancel Workorder">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure you want to cancel this workorder?</p>
      <p class="text-sm text-red-600">This action cannot be undone!</p>
    </div>
    <form method="POST" id="cancelForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>