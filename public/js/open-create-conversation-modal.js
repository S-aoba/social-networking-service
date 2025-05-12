document.addEventListener('DOMContentLoaded', () => {
  const composeConversationButton = document.getElementById('compose-conversation-button');
  composeConversationButton.addEventListener('click', () => {
    const createConversationModal = document.getElementById('create-conversation-modal');
    createConversationModal.classList.remove('hidden');
  })
})