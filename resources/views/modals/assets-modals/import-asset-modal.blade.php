<x-modal name="importAsset" title="Import Assets">
  <form method = "POST" action = "{{ route('assets.import') }}">
      <div class = "flex flex-col gap-3 px-2 sm:px-4">
        @csrf
        <x-label for="file_import" :required="true">Import File</x-label>
        <input type="file" class="filepond-import" name="file_import" id="file_import">
        <x-buttons class="mt-2" type="submit">Submit</x-buttons>
      </div>
    </form>
</x-modal>