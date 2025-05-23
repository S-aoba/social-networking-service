window.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.post-form').forEach((form) => {
    form.addEventListener('submit', async(e) => {
      e.preventDefault();

      const formData = new FormData(form);
      
      const res = await fetch('api/like', {
        method: "POST",
        body: formData
      })

      const data = await res.json();

      if(data.status === 'success') {
        window.location.reload();
      }
      else {
        console.log('error');
      }
    })
  })
})