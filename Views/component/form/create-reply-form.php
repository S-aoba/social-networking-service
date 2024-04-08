<?php
$profile_path = is_null($login_user_profile->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $login_user_profile->getUploadFullPathOfProfileImage();

$csrf_token = Helpers\CrossSiteForgeryProtection::getToken();
?>

<div class="min-h-fit h-fit w-full flex border-b border-slate-200">
  <div class="h-full min-w-18 w-20 flex justify-end py-5">
    <img src="<?= htmlspecialchars($profile_path) ?>" alt="返信ユーザーアイコン" class="w-12 h-12 rounded-full border border-slate-200">
  </div>
  <div class="h-full w-full flex flex-col py-5">
    <form id="createReplyForm" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
      <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
      <textarea id="reply_content" name="reply_content" class="pt-1 pb-3 px-3 resize-none w-full min-h-10 h-10 focus:outline-none" placeholder="返信をポスト" maxlength="255" required></textarea>
      <div id="preview" class="max-h-96 w-full flex justify-center"></div>
      <div class="flex justify-between mt-3 pr-3">
        <label for="image" class="inline-block rounded-full cursor-pointer hover:bg-slate-100 h-9 w-9 p-2 transition-colors duration-300">
          <img src="/images/upload-icon.svg" alt="upload-icon" class="object-fit w-full h-full">
        </label>
        <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif, .mp4" class="hidden">
        <button type="submit" class="px-4 py-2 border border-slate-300 rounded-md hover:bg-slate-200 transition-colors duration-300">投稿する</button>
      </div>
    </form>
  </div>
</div>
<script src="/js/preview.js"></script>
<script src="/js/reply/create-reply.js"></script>
