<x-modal name="approveRequest" title="Approve Request">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure want to approve this request?</p>
      <p class="text-sm text-gray-600">This will create a workorder and proceed with the request.</p>
    </div>
    <form method="POST" id = "approveForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Yes, Approve Request</x-buttons>
    </form>
  </div>
</x-modal>