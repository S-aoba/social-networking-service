<!-- 投稿フォーム -->
<div class="h-fit p-3 border-b border-slate-200">
  <form action="#" method="POST" id="postForm" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <div class="flex h-full">
      <div class="h-10 w-10">
        <!-- TODO: プロフィール画像の取得方法を変えたらnullの場合の処理を追加する -->
        <img src="<?= htmlspecialchars(is_null($login_user_profile_image_path) ? '/images/default-icon.svg' : $login_user_profile_image_path) ?>" alt="プロフィール画像" class="w-8 h-8 rounded-full border border-slate-300">
      </div>
      <div class="w-full flex flex-col ml-4">
        <textarea id="content" name="content" class="p-3 resize-none border rounded-md w-full h-16" placeholder="いまどうしてる?" maxlength="255" required></textarea>
        <label for="image" class="mt-3 inline-block text-white border border-slate-300 rounded-md cursor-pointer hover:bg-slate-200 h-8 w-8 p-1 transition-colors duration-300">
          <img src="/images/upload-icon.svg" alt="upload-icon" class="object-fit w-full h-full">
        </label>
        <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif, .mp4" class="hidden">
        <div id="preview" class="mt-3 max-h-96 w-full flex justify-center">
        </div>
        <div class="flex justify-end">
          <button id="post" type="submit" class="px-4 py-2 border border-slate-300 rounded-md hover:bg-slate-200 transition-colors duration-300">投稿する</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script src="/js/textarea/post-form.js"></script>
