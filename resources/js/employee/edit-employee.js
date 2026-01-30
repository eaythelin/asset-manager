const editButtons = document.querySelectorAll('.editButton');
const form = document.getElementById('editForm');
const firstNameInput = form.querySelector('#edit_first_name');
const lastNameInput = form.querySelector('#edit_last_name');
const select = document.getElementById('edit_selectDepartment');

editButtons.forEach(button => {
  button.addEventListener('click', function() {
    const firstname = this.dataset.firstName;
    const lastname = this.dataset.lastName;
    const department = this.dataset.department;
    let route = this.dataset.route;

    form.action = route;
    firstNameInput.value = firstname;
    lastNameInput.value = lastname;
    select.value = department;
  });
});