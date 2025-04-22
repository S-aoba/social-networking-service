document.addEventListener('DOMContentLoaded', () => {
  const followForm = document.getElementById("follow-form");
  followForm.addEventListener('submit', async(e) => {
    e.preventDefault();
    
    const formData = new FormData(followForm);
    
    const res = await fetch('http://localhost:8000/form/follow', {
      method: "POST",
      body: formData
    })

    const data = await res.json();

    if(data.status === 'success') {
      window.location.reload();
    }
    else {
      console.log(data.status);
    }
  })
});