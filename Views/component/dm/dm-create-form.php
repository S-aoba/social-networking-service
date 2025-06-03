<div class="bg-white py-4 px-2 border-t border-slate-200">
  
  <div id="dm-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>
  
  <form id="direct-message-form" method="POST" class="relative">
    
    <div id="dm-form-toolbar" class="flex items-center justify-between p-1 mb-2 border border-slate-300 rounded-md">
      <div class="flex items-center justify-center size-7 rounded-full transition duration-300 cursor-pointer hover:bg-slate-100">
        <label 
        for="dm-upload-file"
        >
        <img 
            src="/images/upload-icon.svg" 
            alt="upload-icon" 
            class="size-5 cursor-pointer"
            >
            <input
            id="dm-upload-file"
            type="file"
            name="upload-file"
            class="hidden"
            accept="image/png, image/jpg, image/jpeg, image/gif, image/webp"
          >
        </label>
      </div>
    </div>

    <div id="dm-image-preview-area"></div>
    
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
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

<script src="js/dm-form.js"></script>
<script src="js/upload-dm-image.js"></script>