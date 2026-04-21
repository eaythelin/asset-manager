<x-modal name="archiveEmployee" title="Archive Employee">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <div class="space-y-2">
      <p class="text-base font-medium">Archive this employee?</p>
      <p class="text-sm text-gray-600">This will deactivate their account and prevent them from logging in.</p>
    </div>
    <form method="POST" id = "archiveForm">
      @csrf
      @method("DELETE")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>