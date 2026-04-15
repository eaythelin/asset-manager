<x-modal name="permaDeleteUser" title="Permanent Deletion">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Are you sure you want to permanently delete this user account?</p>
      <p class="text-sm text-red-600">This action is irreversible!</p>
    </div>
    <form method="POST" id = "permaDeleteForm">
      @csrf
      @method("DELETE")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>