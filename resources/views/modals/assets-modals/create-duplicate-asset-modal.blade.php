<x-modal name="duplicateModal" title="Duplicate Detected!">
  <div class="flex flex-col gap-4 text-center">
    <p class="text-sm font-medium text-gray-600">
      An asset with this name, serial name, and department already exists.
      This will be combined with the existing asset. Continue?
    </p>
    <div class="flex justify-center gap-2 mt-2">
      <x-buttons type="button" class="bg-gray-800" onclick="document.getElementById('duplicateModal').close()">Cancel</x-buttons>
      <x-buttons type="button" id="confirmMergeBtn">Confirm</x-buttons>
    </div>
  </div>
</x-modal>
