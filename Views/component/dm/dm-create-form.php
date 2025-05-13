<div class="bg-white py-4 px-2 border-t border-slate-200">
  <form action="form/direct-message" method="POST" class="relative">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken
    () ?>">
    <input type="hidden" name="conversation_id" value="<?= $conversation->getId(); ?>">
    <input type="hidden" name="sender_id" value="<?= $authUser->getUserId(); ?>">        
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