const editButtons = document.querySelectorAll('.editButton');

editButtons.forEach(button => {
  button.addEventListener('click', function() {
    const userData = JSON.parse(this.dataset.user);
    
    const selectEmployee = document.getElementById('edit_selectEmployee');
    
    // Check if the employee option exists
    const optionExists = Array.from(selectEmployee.options).some(opt => opt.value == userData.employee_id);
    
    // If it doesn't exist, add it temporarily
    if (!optionExists && userData.employee_id) {
      //creates like for example <option value=5 selected>name here </option>
      //Temporarily adds the current employee option!!!
      const option = new Option(userData.employee_name, userData.employee_id, true, true);
      selectEmployee.add(option);
    }
    
    selectEmployee.value = userData.employee_id;

    const selectRole = document.getElementById('edit_selectRole');
    selectRole.value=userData.role_id;

    document.querySelector('#edit_email').value = userData.email;
    const form = document.getElementById('editForm');
    form.action = userData.route;
  });
});