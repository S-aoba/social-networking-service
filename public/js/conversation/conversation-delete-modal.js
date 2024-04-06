const conversationDeleteModals = document.querySelectorAll('#conversationDeleteModal');
const deleteBtns = document.querySelectorAll('#deleteBtn');

deleteBtns.forEach((deleteBtn) => {
  deleteBtn.addEventListener('click', () => {
    const conversationId = deleteBtn.dataset.conversationId;

    conversationDeleteModals.forEach((conversationDeleteModal) => {
      if (conversationDeleteModal.dataset.conversationId === conversationId) {
        conversationDeleteModal.classList.remove('hidden');

        window.onclick = function (e) {
          if (e.target === conversationDeleteModal) {
            conversationDeleteModal.classList.add('hidden');
          }
        };
      }
    });
  });
});

const conversationCancelBtns = document.querySelectorAll('#postDeleteCancelBtn');

conversationCancelBtns.forEach((btn) => {
  btn.addEventListener('click', () => {
    const conversationId = btn.dataset.conversationId;

    conversationDeleteModals.forEach((conversationDeleteModal) => {
      if (conversationDeleteModal.dataset.conversationId == conversationId) {
        conversationDeleteModal.classList.add('hidden');
      }
    });
  });
});
