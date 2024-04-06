const conversationModal = document.getElementById('conversationModal');
const createConversationBtn = document.getElementById('createConversationBtn');
const createConversationIconBtn = document.getElementById('createConversationIconBtn');

const openConversationModal = (btn) => {
  btn.addEventListener('click', () => {
    conversationModal.classList.remove('hidden');
  });
};

if (createConversationBtn) {
  openConversationModal(createConversationBtn);

}
openConversationModal(createConversationIconBtn);

window.onclick = function (event) {
  if (event.target == conversationModal) {
    conversationModal.classList.add('hidden');
  }
};
