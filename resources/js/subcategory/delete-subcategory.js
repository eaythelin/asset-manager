const deleteBtns = document.querySelectorAll('.deleteBtn');
const form = document.getElementById('deleteForm');

deleteBtns.forEach(button => {
  button.addEventListener('click', function(){
    const route = this.dataset.route;
    form.action = route;
  })
})