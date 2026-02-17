const submitBtns = document.querySelectorAll('.submitBtn');
const form = document.getElementById('submitForm');

submitBtns.forEach(button => {
  button.addEventListener('click', function(){
    let route = this.dataset.route;
    form.action = route;
  })
})