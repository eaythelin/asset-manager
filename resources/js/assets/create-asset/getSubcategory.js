const categorySelect = document.getElementById("category");
const subcategorySelect = document.getElementById("subcategory");

async function loadSubcategories(categoryId){
  //empties subcategory when you change the category
  subcategorySelect.innerHTML = '<option value="" disabled selected>--Select Subcategory</option>';
  subcategorySelect.disabled = true;

  if(!categoryId) return;

  try {
    //fetch subcategories
    const response = await fetch(`/assets/subcategories/${categoryId}`);
    const subCategories = await response.json();
    
    //populate the subcategory!!
    subCategories.forEach(sub => {
      const option = document.createElement('option');
      option.value = sub.id;
      option.textContent = sub.name;
      subcategorySelect.appendChild(option);
    })

    subcategorySelect.disabled = false;

  } catch(error) {
    console.error('Error fetching subcategories: ', error);
  }
}
categorySelect.addEventListener('change', function(){
  loadSubcategories(this.value);
})

document.addEventListener('DOMContentLoaded', () => {
  if(categorySelect.value){
    loadSubcategories(categorySelect.value);
  }
})