const disposeBtns = document.querySelectorAll('.disposeBtn');
const form = document.getElementById('disposeForm');

disposeBtns.forEach(button => {
  button.addEventListener('click', function(){
    let route = this.dataset.route;
    form.action = route;
  })
})