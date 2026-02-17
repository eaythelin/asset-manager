const declineBtns = document.querySelectorAll('.declineBtn');
const form = document.getElementById('declineForm');

declineBtns.forEach(button => {
  button.addEventListener('click', function(){
    let route = this.dataset.route;
    form.action = route;
  })
})