<x-modal :name="'reactivateUser'" title="Reactivate User">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Restore this account?</p>
      <p class="text-sm text-gray-600">The user will regain access to the system.</p>
    </div>
    <form method="POST" id = "reactivateForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>