document.addEventListener('DOMContentLoaded', function (){
  const form = document.getElementById('createAssetForm');
  const duplicateModal = document.getElementById('duplicateModal');
  const confirmBtn = document.getElementById('confirmMergeBtn');

  if(!form) return;

  form.addEventListener('submit', async function(e){
    e.preventDefault();

    const name = document.getElementById('asset_name').value;
    const serialName = document.getElementById('serial_name').value;
    const department = document.getElementById('department').value;

    try {
      const response = await fetch('/assets/create/check-duplicate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
          asset_name: name,
          serial_name: serialName,
          department: department
        })
      });

      const data = await response.json();

      if(data.duplicate){
        duplicateModal.showModal()
      } else {
        form.submit();
      }

    } catch (err) {
      console.log('Duplicate check failed:', err);
      form.submit();
    }
  });

  confirmBtn.addEventListener('click', function(){
    document.getElementById('confirm_merge').value = '1';
    duplicateModal.close();
    form.submit();
  })
})
