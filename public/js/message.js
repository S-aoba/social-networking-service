const messageForm = document.getElementById('messageForm');

messageForm.addEventListener('submit', (e) => {
  e.preventDefault();

  const messageFormData = new FormData(messageForm);

  fetch('/form/message', {
    method: 'POST',
    body: messageFormData,
  }).then((res) => {
    res.json().then((data) => {
      if (data.status === 'success') {
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
      else if(data.status === 'error') {
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
    });
  });
});
