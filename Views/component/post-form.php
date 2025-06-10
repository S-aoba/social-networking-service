<div class="w-full h-fit flex border-b border-slate-200 py-4 pr-4">
  <div role="contributor-icon" class="px-5">
    <div class="size-10 rounded-full overflow-hidden">
      <a href="<?= '/profile?user=' . $authUser->getUsername(); ?>"" class="z-20 relative hover:brightness-90 transition duration-300">
        <img src="<?= $authUser->getImagePath(); ?>" alt="posted-user-icon" class="w-full h-full object-cover">
      </a>
    </div>
  </div>
  <div class="w-full">
    <div id="create-post-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg">
    </div>
    <form id="create-post-form" method="post" enctype="multipart/form-data">
      <input 
        type="hidden" 
        name="csrf_token" 
        value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>"
      >
      <textarea 
        id="content" 
        name="content" 
        class="block p-2 w-full resize-none overflow-hidden focus:outline-none"
        rows="2"
        minlength="1"
        maxlength="144"
        placeholder="いまどうしてる？"
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
          ポストする
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="js/create-post-form.js"></script>
<script src="js/upload-post-form.js"></script>
<script src="js/resize-post-textarea.js"></script>