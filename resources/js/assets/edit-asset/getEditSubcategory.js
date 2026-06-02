const categorySelect = document.getElementById("category");
const subcategorySelect = document.getElementById("subcategory");
const currentSubcategoryId = subcategorySelect ? subcategorySelect.dataset.currentSubcategory || null : null;
let subcategoryTomSelect = null;

async function loadSubcategories(categoryId){
  if(subcategoryTomSelect){
    subcategoryTomSelect.destroy();
    subcategoryTomSelect = null;
  }

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
      subcategorySelect.appendChild(option);
    })

    subcategorySelect.disabled = false;

    subcategoryTomSelect = makeCreatableSelect('#subcategory', subcategorySelect.dataset.createUrl, {
      placeholder: '--Select Subcategory--',
      extraData: { category_id: categoryId },
      onInitialize: function() {
        this.wrapper.classList.remove('invisible');
        if(currentSubcategoryId) {
          this.setValue(currentSubcategoryId);
        }
      }
    });

  }catch(error){
    console.log('Error fetching subcategories: ', error);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  if(window.categoryTomSelect) {
    window.categoryTomSelect.on('change', function(value) {
      loadSubcategories(value);
    });
  }

  if(categorySelect && categorySelect.value){
    loadSubcategories(categorySelect.value);
  }
});
