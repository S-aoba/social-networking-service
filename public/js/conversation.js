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

const conversationMenuIcons = document.querySelectorAll('#conversation-menu-icon');

conversationMenuIcons.forEach((icon) => {
  icon.addEventListener('click', () => {
    const conversationMenu = icon.querySelector('#conversation-menu');

    conversationMenu.classList.toggle('hidden');
  });
});

const conversationDeleteModal = document.getElementById('conversationDeleteModal');
const deleteBtns = document.querySelectorAll('#deleteBtn');

deleteBtns.forEach((deleteBtn) => {
  deleteBtn.addEventListener('click', () => {
    conversationDeleteModal.classList.remove('hidden');
  });
});

const conversationCancelBtns = document.querySelectorAll('#postDeleteCancelBtn');

conversationCancelBtns.forEach((btn) => {
  btn.addEventListener('click', () => {
    conversationDeleteModal.classList.add('hidden');
  });
});

window.onclick = function (e) {
  if (e.target === conversationDeleteModal) {
    conversationDeleteModal.classList.add('hidden');
  }
};
