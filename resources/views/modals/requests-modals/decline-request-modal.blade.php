<x-modal name="declineRequest" title="Decline Request">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure want to decline this request?</p>
      <p class="text-sm text-gray-600">This action cannot be undone.</p>
    </div>
    <form method="POST" id = "declineForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Yes, Decline Request</x-buttons>
    </form>
  </div>
</x-modal>