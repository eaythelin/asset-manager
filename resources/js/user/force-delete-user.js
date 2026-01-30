const permaDeleteBtns = document.querySelectorAll('.permaDeleteBtn');

permaDeleteBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    document.getElementById('permaDeleteForm').action = route;
  });
});