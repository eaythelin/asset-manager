const categorySelect = document.getElementById("category");
const tbody = document.getElementById('subcategoryTable');

//function to render table
function renderTable(subcategories){
  tbody.innerHTML = '';

  if(!subcategories || subcategories.length === 0){
    tbody.innerHTML = '<tr><td colspan="3" class="p-3 text-center text-gray-500">No subcategories found</td></tr>';
    return;
  }

  subcategories.forEach((sub, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <th class = "p-3 text-center">${index + 1}</th>
      <td class = "p-3">${sub.name}</td>
      <td class = "p-3">${sub.assets_count || 0}</td>
    `;
    tbody.appendChild(tr);
  })
}

categorySelect.addEventListener("change", async function() {
  const categoryId = this.value;

  if(!categoryId) return;

  try{
    const response = await fetch(`/dashboard/subcategories/${categoryId}`);
    const subCategories = await response.json();
    
    // render table!
    renderTable(subCategories);

  } catch(error){
    console.error('Error fetching subcategories: ', error);
    tbody.innerHTML = '<tr><td colspan="3" class="p-3 text-center text-red-500">Error loading data</td></tr>';
  }
})

document.addEventListener('DOMContentLoaded', async() => {
  if(categorySelect.value){
    categorySelect.dispatchEvent(new Event('change'));
  }
})