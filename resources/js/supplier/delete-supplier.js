const deleteBtns = document.querySelectorAll('.deleteBtn');
const form = document.getElementById('deleteForm');

deleteBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    deleteForm.action = route;
  });
});