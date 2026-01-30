<x-modal name="permaDeleteUser" title="Permanent Deletion">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <h2 class="text-base">Are you sure you want to permanently delete this user account?</h2>
    <p class="text-sm italic text-red-600">This action is irreversible!</p>
    <form method="POST" id = "permaDeleteForm">
      @csrf
      @method("DELETE")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>