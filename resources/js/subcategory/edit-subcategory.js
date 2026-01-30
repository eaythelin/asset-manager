const editButtons = document.querySelectorAll(".editBtn");
const subCategoryname = document.querySelector("#edit_name");
const description = document.querySelector("#edit_description");
const form = document.getElementById("editForm");
const select = document.getElementById("edit_selectCategory");

editButtons.forEach(button => {
  button.addEventListener('click', function(){
    const subCategory = JSON.parse(this.dataset.subcategory);
    subCategoryname.value = subCategory.name;
    select.value = subCategory.category_id;
    description.value = subCategory.description;
    form.action = subCategory.route;
  })
})