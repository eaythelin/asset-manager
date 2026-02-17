const deleteButtons = document.querySelectorAll('.deleteButton');
const questionText = document.querySelector('.deleteQuestion');
const warningText = document.querySelector('.deleteWarning');
const form = document.getElementById('deleteForm');

deleteButtons.forEach(button => {
  button.addEventListener('click', function() {
    const route = this.dataset.route;
    const hasUser = this.dataset.hasUser == 1;

    if (hasUser) {
      questionText.textContent = "Are you sure you want to delete this employee?";
      warningText.textContent = "This employee has an existing user account. This action is irreversible!";
    } else {
      questionText.textContent = "Are you sure you want to delete this employee?";
      warningText.textContent = "This action cannot be undone.";
    }

    form.action = route;
  });
});