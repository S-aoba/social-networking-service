window.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.post-form').forEach((form) => {
    form.addEventListener('submit', async(e) => {
      e.preventDefault();

      const formData = new FormData(form);
      // フォーム内のエラーメッセージ要素を取得
      const errorMessage = form.querySelector('.like-error-message');
      if (errorMessage) {
        errorMessage.classList.add('hidden');
        errorMessage.textContent = '';
      }
      
      const res = await fetch('api/like', {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if(data.status === 'success') {
        window.location.reload();
      }
      else if (errorMessage) {
        errorMessage.textContent = data.message;
        errorMessage.classList.remove('hidden');
      }
    });
  });
});