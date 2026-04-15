const cancelBtns = document.querySelectorAll('.cancelBtn');

cancelBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    document.getElementById('cancelForm').action = route;
  });
});