document.addEventListener('DOMContentLoaded', function() {
    const deletePostForms = document.querySelectorAll('.delete-post-form');

    deletePostForms.forEach((form) => {
      form.addEventListener('submit', async(e) => {
        e.preventDefault();
  
        const formData = new FormData(form);

        const errorMessage = document.querySelector('.delete-post-error-message');
        errorMessage.textContent = '';
        errorMessage.classList.add('hidden');
  
        const res = await fetch('api/delete/post', {
          method: 'POST',
          body: formData
        });
  
        const data = await res.json();
  
        if(data.status === 'success') {
          window.location.reload();
        }
        else {
          errorMessage.textContent = data.message;
          errorMessage.classList.remove('hidden');
        }
      })
    })
  })