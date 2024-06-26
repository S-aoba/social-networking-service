const deleteReplyForms = document.querySelectorAll('#deleteReplyForm');

deleteReplyForms.forEach((deleteReplyForm) => {
  deleteReplyForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const deleteReplyFormData = new FormData(deleteReplyForm);

    fetch('/form/reply/delete', {
      method: 'POST',
      body: deleteReplyFormData,
    }).then((res) => {
      res.json().then((data) => {
        if (data.status === 'success') {
          setTimeout(() => {
            location.reload();
          }, 1000);
        } else if (data.success === 'error') {
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      });
    });
  });
});
