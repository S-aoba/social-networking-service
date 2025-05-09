document.addEventListener('DOMContentLoaded', () => {
  const createConversationForms = document.querySelectorAll('.create-conversation-form');
  createConversationForms.forEach(form => {
    form.addEventListener('click', (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      
      fetch('form/conversation', {
        method: 'POST',
        body: formData,
      })
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          throw new Error('Network response was not ok');
        }
      })
      .then(data => {
        if (data.status === 'success') {
          window.location.reload();
        } else {
          console.error('Error creating conversation:', data.error);
        }
      })
    })
  })

  const composeConversationButton = document.getElementById('compose-conversation-button');
  composeConversationButton.addEventListener('click', () => {
    const createConversationModal = document.getElementById('create-conversation-modal');
    createConversationModal.classList.remove('hidden');
  })
})