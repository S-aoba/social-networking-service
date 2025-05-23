document.addEventListener('DOMContentLoaded', () => {
  const followForm = document.getElementById("follow-form");
  const errorMessage = document.getElementById('follow-error-message');

  followForm.addEventListener('submit', async(e) => {
    e.preventDefault();
    
    const formData = new FormData(followForm);
    errorMessage.classList.add('hidden');
    errorMessage.textContent = '';
    
    const res = await fetch('api/follow', {
      method: "POST",
      body: formData
    })

    const data = await res.json();

    if(data.status === 'success') {
      window.location.reload();
    }
    else {
      errorMessage.textContent = data.message;
      errorMessage.classList.remove('hidden');
    }
  })
});