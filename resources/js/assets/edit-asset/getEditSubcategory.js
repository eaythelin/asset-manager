const categorySelect = document.getElementById("category");
const subcategorySelect = document.getElementById("subcategory");
const currentSubcategoryId = subcategorySelect.dataset.currentSubcategory || null;

async function loadSubcategories(categoryId){
  console.log(categoryId);
  subcategorySelect.innerHTML = '<option value="" disabled selected>--Select Subcategory</option>';
  subcategorySelect.disabled = true;

  if(!categoryId) return;

  try {
    const response = await fetch(`/assets/subcategories/${categoryId}`);
    const subCategories = await response.json();

    subCategories.forEach(sub => {
      const option = document.createElement('option');
      option.value = sub.id;
      option.textContent = sub.name;

      if(sub.id == currentSubcategoryId){
        option.selected = true;
      }
      subcategorySelect.appendChild(option);
    })

    subcategorySelect.disabled = false;
    
  }catch(error){
    console.log('Error fetching subcategories: ', error);
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