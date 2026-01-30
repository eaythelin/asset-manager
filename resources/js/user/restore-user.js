const restoreButtons = document.querySelectorAll('.restoreButton');

restoreButtons.forEach(button => {
  button.addEventListener('click', function() {
    const route = this.dataset.route;
    document.getElementById('reactivateForm').action = route;
  });
});