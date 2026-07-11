const editButtons = document.querySelectorAll('.editButton');
const form = document.getElementById('editForm');
const nameInput = form.querySelector('#edit_name');
const isMaintenance = form.querySelector('#edit_is_maintenance')
const select = document.getElementById('edit_selectDepartment');
const empNoInput = document.getElementById('edit_emp_no');

editButtons.forEach(button => {
  button.addEventListener('click', function() {
    const name = this.dataset.name;
    const department = this.dataset.department;
    const is_maintenance = this.dataset.maintenance
    const employee_no = this.dataset.empno
    let route = this.dataset.route;

    form.action = route;
    nameInput.value = name;
    isMaintenance.checked = is_maintenance === 'true' || is_maintenance === '1';
    select.value = department;
    empNoInput.value = employee_no;
  });
});
