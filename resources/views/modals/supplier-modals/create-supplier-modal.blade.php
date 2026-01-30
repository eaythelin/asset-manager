<x-modal name="createSupplier" title="Create Supplier">
  <form method="POST" action="{{ route('suppliers.store') }}">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      <x-label for="create_supplier_name" :required="true">Supplier Name</x-label>
      <x-modal-input-box id="create_supplier_name" name="name" autocomplete="off" />
      <x-label for="create_contact_person">Contact Person</x-label>
      <x-modal-input-box id="create_contact_person" name="contact_person" />
      <x-label for="create_email">Email</x-label>
      <x-modal-input-box id="create_email" name="email" autocomplete="email" />
      <x-label for="create_phone_number">Phone Number</x-label>
      <x-modal-input-box id="create_phone_number" name="phone_number" />
      <x-label for="create_address">Address</x-label>
      <x-modal-text-area-box id="create_address" name="address" autocomplete="supplier-address" />
      <x-buttons class="mt-2" type="submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>