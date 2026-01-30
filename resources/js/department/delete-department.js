const deleteButtons = document.querySelectorAll('.deleteButton');
const deleteForm = document.getElementById('deleteForm');

deleteButtons.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    deleteForm.action = route;
  });
});