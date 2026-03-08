async function loadSubcategories(categoryId){
  const subcategorySelect = document.getElementById("subcategory");
  //empties subcategory when you change the category
  subcategorySelect.innerHTML = '<option value="" disabled selected>--Select Subcategory</option>';
  subcategorySelect.disabled = true;

  if(!categoryId) return;

  try {
    //fetch subcategories
    const response = await fetch(`/requests/subcategories/${categoryId}`);
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

// REPLACE the categorySelect.addEventListener with this:
document.addEventListener('change', function(e) {
  if(e.target.id === 'category') {
    loadSubcategories(e.target.value);
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const categorySelect = document.getElementById("category");
  if(categorySelect && categorySelect.value){
    loadSubcategories(categorySelect.value);
  }
})