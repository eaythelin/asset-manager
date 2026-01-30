const editBtns = document.querySelectorAll('.editBtn');
const nameInput = document.getElementById('edit_category_name');
const descInput = document.getElementById('edit_description');
const editForm = document.getElementById('editForm');

editBtns.forEach(button => {
  button.addEventListener('click', function(){
    const categoryData = JSON.parse(this.dataset.category);
    nameInput.value = categoryData.name;
    descInput.value = categoryData.description;
    editForm.action = categoryData.route;
  })
})