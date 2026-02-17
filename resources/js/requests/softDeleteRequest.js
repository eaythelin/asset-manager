const cancelBtns = document.querySelectorAll('.cancelBtn');
const form = document.getElementById('cancelForm');

cancelBtns.forEach(button => {
  button.addEventListener('click', function(){
    let route = this.dataset.route;
    form.action = route;
  })
})