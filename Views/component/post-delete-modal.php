<!-- PostDeleteModal -->
<div id="postDeleteModal" data-post-id="<?= $data['post']->getId() ?>" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <div class="bg-white p-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">削除してもよろしですか？この操作を取り消すことはできません</h2>
    <div class="w-full h-full flex space-x-3 items-center justify-center rounded-md">
      <form id="deletePostForm" method="POST" action="#">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
        <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
        <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
        <button type="submit" class="bg-red-500 text-white hover:bg-red-700 py-2 px-3 rounded-md transition-colors duration-300">削除</button>
      </form>
      <div>
        <button id="postDeleteCancelBtn" data-post-id="<?= $data['post']->getId() ?>" type="button" class="border border-slate-300 py-2 px-3 hover:bg-slate-200 rounded-md transition-colors duration-300">キャンセル</button>
      </div>
    </div>
  </div>
</div>
