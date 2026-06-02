const editButtons = document.querySelectorAll('.editButton');
const form = document.getElementById('editForm');
const nameInput = form.querySelector('#edit_name');
const select = document.getElementById('edit_selectDepartment');

editButtons.forEach(button => {
  button.addEventListener('click', function() {
    const name = this.dataset.name;
    const department = this.dataset.department;
    let route = this.dataset.route;

    form.action = route;
    nameInput.value = name;
    select.value = department;
  });
});
