const editButtons = document.querySelectorAll('.editButton');
const form = document.getElementById('editForm');
const nameInput = document.querySelector('#edit_department_name');
const descInput = document.querySelector('#edit_description');

editButtons.forEach(button => {
  button.addEventListener('click', function() {
    const name = this.dataset.name;
    const description = this.dataset.description;
    let route = this.dataset.route;

    form.action = route;
    nameInput.value = name;
    descInput.value = description;
  });
});