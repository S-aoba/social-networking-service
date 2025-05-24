<div class="bg-white py-4 px-2 border-t border-slate-200">
  <div id="dm-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>

  <form id="direct-message-form" method="POST" class="relative">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken
    () ?>">
    <input type="hidden" name="conversation_id" value="<?= $conversation->getId(); ?>">
    <textarea 
      id="content"
      name="content" 
      class="w-full resize-none h-10 bg-slate-200 text-sm p-2 border border-slate-200 rounded-md focus:outline-none" 
      placeholder="新しいメッセージを作成"
      ></textarea>
    <button 
      type="submit" 
      class="absolute right-2 px-4 py-2 rounded-md focus:outline-none"
    >
      <img 
        src="images/send-icon.svg" 
        alt="direct-message-send-icon" 
        class="size-5"
      >
    </button>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const directMessageForm = document.getElementById('direct-message-form');
    const errorMessage = document.getElementById('dm-error-message');

    directMessageForm.addEventListener('submit', async(e) => {
      e.preventDefault();

      const formData = new FormData(directMessageForm);
      errorMessage.textContent = '';
      errorMessage.classList.add('hidden');

      const res = await fetch('api/direct-message', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();

      if(data.status === 'success') {
        window.location.href = data.redirect;
      }
      else {
        errorMessage.textContent = data.message;
        errorMessage.classList.remove('hidden');
      }
    })
  })
</script>