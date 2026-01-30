const deleteButtons = document.querySelectorAll('.deleteButton');
const text = document.querySelector('.deleteText');
const form = document.getElementById('deleteForm');

deleteButtons.forEach(button => {
  button.addEventListener('click', function() {
    const route = this.dataset.route;
    const hasUser = this.dataset.hasUser == 1;

    text.textContent = hasUser 
      ? "This employee has an existing user account. Are you sure you want to delete this?" 
      : "This will permanently delete the employee!";

    form.action = route;
  });
});