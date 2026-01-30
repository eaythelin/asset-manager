<x-modal name="disposeAsset" title="Dispose Asset" x-data="{selectedMethod: ''}">
  <form method="POST" id="disposeForm">
    @csrf
    @method("DELETE")
    <div class="flex flex-col gap-3 px-2 sm:px-4">
      <x-label for="disposal_method" :required="true">Disposal Method</x-label>
      <select name="disposal_method" id="disposal_method" x-model="selectedMethod" class="select w-full rounded-xl">
        @foreach($disposalMethods as $method)
          <option value="{{ $method->value }}">{{ $method->label() }}</option>
        @endforeach
      </select>
      <x-label for="reason">Reason</x-label>
      <x-modal-text-area-box name="reason" id="reason"/>
      <x-buttons class="mt-2" type="submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>