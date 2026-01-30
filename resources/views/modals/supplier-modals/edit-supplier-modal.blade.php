<x-modal name="editSupplier" title="Edit Supplier">
  <form method="POST" id="editForm">
    <div class = "flex flex-col gap-3 px-2 sm:px-4">
      @csrf
      @method('PUT')
      <x-label for="edit_supplier_name" :required="true">Supplier Name</x-label>
      <x-modal-input-box id="edit_supplier_name" name="name" autocomplete="off" />
      <x-label for="edit_contact_person">Contact Person</x-label>
      <x-modal-input-box id="edit_contact_person" name="contact_person" />
      <x-label for="edit_email">Email</x-label>
      <x-modal-input-box id="edit_email" name="email" autocomplete="email" />
      <x-label for="edit_phone_number">Phone Number</x-label>
      <x-modal-input-box id="edit_phone_number" name="phone_number" />
      <x-label for="edit_address">Address</x-label>
      <x-modal-text-area-box id="edit_address" name="address" autocomplete="supplier-address" />
      <x-buttons class="mt-2" type="submit">Submit</x-buttons>
    </div>
  </form>
</x-modal>