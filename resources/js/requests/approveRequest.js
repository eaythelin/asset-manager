const approveBtns = document.querySelectorAll('.approveBtn');
const form = document.getElementById('approveForm');

approveBtns.forEach(button => {
  button.addEventListener('click', function(){
    let route = this.dataset.route;
    form.action = route;
  })
})