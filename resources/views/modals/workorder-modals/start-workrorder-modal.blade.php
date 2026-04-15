<x-modal name="startWorkorder" title="Start Workorder">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure you want to start this workorder?</p>
      <p class="text-sm text-gray-600">Start and end dates will be locked after confirming.</p>
    </div>
    <form method="POST" id="startForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>