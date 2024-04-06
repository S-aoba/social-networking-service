const deleteConversationForms = document.querySelectorAll('#deleteConversationForm');

deleteConversationForms.forEach((deleteConversationForm) => {
  deleteConversationForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(deleteConversationForm);

    fetch('/form/conversation/delete', {
      method: 'POST',
      body: formData,
    }).then((res) => {
      res.json().then((data) => {
        if (data.status === 'success') {
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      });
    });
  });
});
