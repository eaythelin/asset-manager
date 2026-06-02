const subcategorySelect = document.getElementById("subcategory");
const categorySelect = document.getElementById("category");
let subcategoryTomSelect = null;

async function loadSubcategories(categoryId){
  //empties subcategory when you change the category
  if(subcategoryTomSelect){
    subcategoryTomSelect.destroy();
    subcategoryTomSelect = null;
  }

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

    subcategoryTomSelect = makeCreatableSelect('#subcategory', subcategorySelect.dataset.createUrl, {
      placeholder: '--Select Subcategory--',
      extraData: { category_id: categoryId },
      onInitialize: function() {
        this.wrapper.classList.remove('invisible');
      }
    })

  } catch(error) {
    console.error('Error fetching subcategories: ', error);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // wait for assetTomSelect to initialize
  setTimeout(() => {
    if(window.categoryTomSelect) {
        window.categoryTomSelect.on('change', function(value) {
            loadSubcategories(value);
        });
    }

    // load subcategories on page load if category is already selected
    if(categorySelect && categorySelect.value) {
        loadSubcategories(categorySelect.value);
    }
  }, 1);
});
