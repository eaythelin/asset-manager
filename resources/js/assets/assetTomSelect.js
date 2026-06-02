const categorySelect = document.getElementById('category');
const departmentSelect = document.getElementById('department');
const supplierSelect = document.getElementById('supplier');

function showToast(message){
  const toast = document.getElementById('ajaxToast');
  const msg = document.getElementById('ajaxToastMSG');
  msg.textContent = message;
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 3000);
}

window.makeCreatableSelect = function(selector, createURL, options = {}){
  return new TomSelect(selector, {
    placeholder: options.placeholder ?? '--Select--',
    create: createURL ? function(input, callback){
      fetch(createURL, {
        method: 'POST',
        headers : {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({name: input, ...options.extraData})
      })
      .then(res => {
        if(!res.ok){
          return res.json().then(err => {
            const message = Object.values(err.errors)[0][0]
            showToast(message)
            callback()
          })
        }
        return res.json()
      })
      .then(data => {if(data) callback({ value: data.id, text: data.name })});
    } : false,
    createOnBlur: false,
    ...options
  });
}

if(categorySelect){
    window.categoryTomSelect = makeCreatableSelect('#category', categorySelect.dataset.createUrl, {
        placeholder: '--Select Category--',
    })
}


if(departmentSelect){
  makeCreatableSelect('#department', departmentSelect.dataset.createUrl, {
    placeholder: '--Select Department--'
  })
}

if(supplierSelect){
  makeCreatableSelect('#supplier', supplierSelect.dataset.createUrl, {
    placeholder: '--Select Supplier--'
  })
}
