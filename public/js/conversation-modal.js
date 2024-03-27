const conversationModal = document.getElementById('conversationModal');
const newConversationButton = document.getElementById('newConversationButton1');

newConversationButton.onclick = function () {
  conversationModal.classList.remove('hidden');
};

window.onclick = function (event) {
  if (event.target == conversationModal) {
    conversationModal.classList.add('hidden');
  }
};
