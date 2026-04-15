const deleteBtns = document.querySelectorAll('.deleteBtn');
const deleteForm = document.getElementById('deleteForm');

deleteBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    deleteForm.action = route;
  });
});