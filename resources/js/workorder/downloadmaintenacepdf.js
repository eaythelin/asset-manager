const downloadBtns = document.querySelectorAll('.downloadBtn');

downloadBtns.forEach(button => {
  button.addEventListener('click', function() {
    let route = this.dataset.route;
    window.location.href = route;
  });
});
