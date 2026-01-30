const acquisitionDateInput = document.getElementById('acquisition_date');
const usefulLifeInput = document.getElementById('useful_life_in_years');
const endOfLifeDateInput = document.getElementById('end_of_life_date');

function calculateEndofLife(){
  const acquisitionDate = acquisitionDateInput.value;
  const usefulLife = parseInt(usefulLifeInput.value);

  //checks if both fields hab values
  if(!acquisitionDate || !usefulLife || usefulLife <= 0){
    endOfLifeDateInput.value = '';
    return;
  }

  const startDate = new Date(acquisitionDate);
  const endDate = new Date(startDate);
  endDate.setFullYear(startDate.getFullYear() + usefulLife);

  //format the date as Y-MM-DD for the input box
  const formattedDate = endDate.toISOString().split('T')[0];
  endOfLifeDateInput.value = formattedDate;
}

acquisitionDateInput.addEventListener('change', calculateEndofLife);
usefulLifeInput.addEventListener('change', calculateEndofLife);