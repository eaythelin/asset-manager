window.loadSubcategories = async function(categoryId, currentSubcategoryId = null){
  const subcategorySelect = document.getElementById("subcategory");
  
  if(!currentSubcategoryId){
    currentSubcategoryId = subcategorySelect.dataset.currentSubcategory || null;
  }

  subcategorySelect.innerHTML = '<option value="" disabled selected>--Select Subcategory</option>';
  subcategorySelect.disabled = true;

  if(!categoryId) return;

  try {
    const response = await fetch(`/requests/subcategories/${categoryId}`);
    const subCategories = await response.json();
    
    subCategories.forEach(sub => {
      const option = document.createElement('option');
      option.value = sub.id;
      option.textContent = sub.name;
      if(currentSubcategoryId && sub.id == currentSubcategoryId){
        option.selected = true;
      }
      subcategorySelect.appendChild(option);
    })

    subcategorySelect.disabled = false;

  } catch(error) {
    console.error('Error fetching subcategories: ', error);
  }
}