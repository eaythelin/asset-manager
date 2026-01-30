<x-modal :name="'reactivateUser'" title="Reactivate User">
  <div class = "flex flex-col gap-6 text-center sm:px-4">
    <h2 class = "text-base"> Restore this account? The user will regain access to the system.</h2>
    <form method="POST" id = "reactivateForm">
      @csrf
      @method("PUT")
      <x-buttons class="w-full" type="Submit">Submit</x-buttons>
    </form>
  </div>
</x-modal>