const deleteButtons = document.querySelectorAll('.deleteButton');

deleteButtons.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    document.getElementById('deleteForm').action = route;
  });
});