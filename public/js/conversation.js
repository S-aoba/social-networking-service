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
