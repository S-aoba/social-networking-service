document.addEventListener('DOMContentLoaded', () => {
  const conversationForms = document.querySelectorAll('.create-conversation-form');
  const errorMessage = document.getElementById('conversation-error-message');

  conversationForms.forEach((form) => {
    form.addEventListener('click', async(e) => {
      e.preventDefault();

      const formData = new FormData(form);
      errorMessage.textContent = '';
      errorMessage.classList.add('hidden');

      const res = await fetch('api/conversation', {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if(data.status === 'success') {
        // Modalを閉じる処理
        window.location.href = data.redirect;
      }
      else {
        const messages = data.message;

        errorMessage.innerHTML = "<ul>" + Object.values(messages).map(msg => `<li>${msg}</li>`).join("") + "</ul>";
        errorMessage.classList.remove("hidden");
      }
    })
  })
})