const replyForm = document.getElementById('createReplyForm');

replyForm.addEventListener('submit', (e) => {
  e.preventDefault();

  const replyFormData = new FormData(replyForm);

  fetch('/form/reply', {
    method: 'POST',
    body: replyFormData,
  }).then((res) => {
    res.json().then((data) => {
      if (data.status === 'success') {
        setTimeout(() => {
          location.reload();
        }, 1000);
      } else if (data.status === 'error') {
        setTimeout(() => {
          location.reload();
        }, 1000);
        alert('返信に失敗しました')
      }
    });
  });
});
