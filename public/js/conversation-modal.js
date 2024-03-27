const conversationModal = document.getElementById('conversationModal');
const createConversationBtn = document.getElementById('createConversationBtn');

createConversationBtn.onclick = function () {
  conversationModal.classList.remove('hidden');
};

window.onclick = function (event) {
  if (event.target == conversationModal) {
    conversationModal.classList.add('hidden');
  }
};
