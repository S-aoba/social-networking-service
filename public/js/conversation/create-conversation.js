const newConversationForms = document.querySelectorAll('.newConversationForm');

newConversationForms.forEach((newConversationForm) => {
  newConversationForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(newConversationForm);

    fetch('/form/conversation', {
      method: 'POST',
      body: formData,
    }).then((res) => {
      res.json().then((data) => {
        if (data.status === 'success') {
          // TODO:受け取ったデータを使って画面を遷移させる
          location.href = '/message/' + data.conversation_id;
        }
      });
    });
  });
});
