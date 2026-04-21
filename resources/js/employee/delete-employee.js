const archiveButtons = document.querySelectorAll('.archiveButton');
const form = document.getElementById('archiveForm');

archiveButtons.forEach(button => {
  button.addEventListener('click', function() {
    const route = this.dataset.route;
    form.action = route;
  });
});