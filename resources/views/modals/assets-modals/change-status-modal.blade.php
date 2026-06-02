<x-modal name="changeStatus" title="Change Status">
  <form method="POST" id="changeStatusForm">
    @csrf
    @method('PUT')
    <div class="flex flex-col gap-3 px-2 sm:px-4">
      <x-label for="status" :required="true">Status</x-label>
      <select name="status" id="status" class="select w-full rounded-xl">
        <option value="" disabled>--Select Status--</option>
      </select>
      <x-label for="notes">Notes</x-label>
      <x-modal-text-area-box name="notes" id="notes"/>
      <x-buttons class="mt-2" type="submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>
