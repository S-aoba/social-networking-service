<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <div class="flex items-center gap-x-2 p-2">
    <?php include "Views/component/undo.php" ?>
    <span class="font-semibold">ポスト</span>
  </div>
  <div class="w-full h-fit border-b border-slate-200">
    <?php include "Views/component/article.php" ?>
  </div>
  <div class="w-full h-fit flex border-b border-slate-200 py-4 pr-4">
    <div role="contributor-icon" class="px-5">
      <div class="size-10 rounded-full overflow-hidden">
        <a href="<?= '/profile?user=' . $authUser->getUsername(); ?>"" class="z-20 relative hover:brightness-90 transition duration-300">
          <img src="<?= $authUser->getImagePath(); ?>" alt="posted-user-icon" class="w-full h-full object-cover">
        </a>
      </div>
    </div>
    <div class="w-full">
      <div id="reply-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>
      <form id="reply-form" method="post" enctype="multipart/form-data">
        <input 
          type="hidden" 
          name="csrf_token" 
          value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>"
        >
        <input 
          type="hidden" 
          name="parent_post_id" 
          value="<?= $data['post']->getId(); ?>"
        >
        <textarea 
          id="content" 
          name="content" 
          class="block p-2 w-full resize-none overflow-hidden focus:outline-none"
          rows="2"
          minlength="1"
          maxlength="144"
          placeholder="返信をポスト"
          require
        ></textarea>
        <div class="w-full flex flex-col justify-center items-start">
          <div id="preview-container" class="hidden pb-4 w-full h-96 overflow-hidden"></div>
          <div id="post-form-tool-bar" class="w-full flex items-center justify-between">
            <label 
              for="upload-file" 
              class="p-2 hover:cursor-pointer hover:bg-slate-100 rounded-full transition duration-300"
            >
              <img src="/images/upload-icon.svg" alt="upload-icon" class="size-5">
              <input 
                id="upload-file" 
                type="file" 
                name="upload-file" 
                class="hidden" 
                value=""
                accept="image/png, image/jpg, image/jpeg, image/gif, image/webp"
              >
            </label>
            <button 
              type="submit" 
              class="py-2 px-4 rounded-4xl text-sm bg-gray-500/60 text-white font-semibold hover:bg-slate-800 transition duration-300 focus:bg-slate-800 hover:cursor-pointer"
            >
            返信
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="w-full divide-y divide-slate-200">
    <?php
      if ($replies !== null) {
          foreach ($replies as $data) {
              include "Views/component/article.php";
          }
      }
    ?>
  </div>
</div>
<script src="js/like.js"></script>
<script src="js/open-delete-post-menu.js"></script>
<script src="js/open-delete-post-modal.js"></script>
<script src="js/create-reply-form.js"></script>
<script src="js/upload-reply-file.js"></script>
<script src="js/resize-reply-textarea.js"></script>
<script src="js/delete-post-form.js"></script>
