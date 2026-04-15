const startBtns = document.querySelectorAll('.startBtn');

startBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    document.getElementById('startForm').action = route;
  });
});