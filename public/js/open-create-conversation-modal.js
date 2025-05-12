document.addEventListener('DOMContentLoaded', () => {
  const composeConversationButtons = document.querySelectorAll('.compose-conversation-button');

  composeConversationButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const createConversationModal = document.getElementById('create-conversation-modal');
      createConversationModal.classList.remove('hidden');
    })
  })
})