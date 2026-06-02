const changeBtns = document.querySelectorAll('.changeBtn');
const form = document.getElementById('changeStatusForm');
const select = document.getElementById('status');

const statusOptions = {
  'active': [{ value: 'under_service', label: 'Under Service' }],
  'under_service': [{ value: 'active', label: 'Active' }],
  'expired': [{ value: 'under_service', label: 'Under Service' }],
}

changeBtns.forEach(button => {
  button.addEventListener('click', function(){
    let route = this.dataset.route;
    let status = this.dataset.status;

    form.action = route;

    select.innerHTML = '<option value="" disabled selected>--Select Status--</option>';
    statusOptions[status].forEach(opt => {
      select.innerHTML += `<option value="${opt.value}">${opt.label}</option>`;
    });
  })
})
