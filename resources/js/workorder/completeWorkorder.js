const completeBtns = document.querySelectorAll('.completeBtn');

completeBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    document.getElementById('completeForm').action = route;
  });
});