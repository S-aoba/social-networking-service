<div class="flex flex-col pt-3 pb-5 border-b border-slate-300">
  <div class="w-full flex item-center px-3">
    <div class="w-14 h-full">
      <!-- ログインユーザーのプロフィールを持ってくる -->
      <img src="/images/default-icon.svg" alt="返信ユーザーアイコン" class="h-10 w-10 border border-slate-300 rounded-full">
    </div>
    <div class="w-full flex flex-col">
      <form id="createReplyForm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
        <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
        <div class="w-full flex items-center px-2">
          <textarea name="reply_content" id="reply_content" placeholder="返信をポスト" class="p-3 resize-none w-full min-h-14 focus:outline-none" maxlength="255" required></textarea>
        </div>
        <div class="w-full flex flex-col space-y-3 mt-2">
          <label for="image" class="inline-block rounded-full cursor-pointer hover:bg-slate-100 h-9 w-9 p-2 transition-colors duration-300">
            <img src="/images/upload-icon.svg" alt="upload-icon" class="object-fit w-full h-full">
          </label>
          <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif, .mp4" class="hidden">
          <div id="preview" class="max-h-96 w-full flex justify-center">
          </div>
          <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 border border-slate-300 rounded-md hover:bg-slate-200 transition-colors duration-300">返信</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="/js/preview.js"></script>
<script src="/js/reply/create-reply.js"></script>
