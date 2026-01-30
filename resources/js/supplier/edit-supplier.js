const editBtns = document.querySelectorAll('.editBtn');
const nameInput = document.getElementById('edit_supplier_name');
const contactPersonInput = document.getElementById('edit_contact_person');
const emailInput = document.getElementById('edit_email');
const phoneNumberInput = document.getElementById('edit_phone_number');
const addressInput = document.getElementById('edit_address');
const editForm = document.getElementById('editForm');

editBtns.forEach(button => {
  button.addEventListener('click', function(){
    const supplierData = JSON.parse(this.dataset.supplier);
    nameInput.value = supplierData.name;
    contactPersonInput.value = supplierData.contact_person;
    emailInput.value = supplierData.email;
    phoneNumberInput.value = supplierData.phone_number;
    addressInput.value = supplierData.address;
    
    editForm.action = supplierData.route;
  })
})