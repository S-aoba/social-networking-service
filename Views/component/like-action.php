<form method="POST" class="post-form w-full h-full flex items-center">
  <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
  <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
  <button type="submit" name="like-button" class="z-10 hover:bg-red-100 rounded-full p-2 hover:cursor-pointer transition duration-300">
  <?php if($data['likeCount'] > 0): ?>
    <div class="flex items-center space-x-1">
      <?php if($data['liked']): ?>
        <img src="/images/like-icon.svg" alt="unlike-icon" class="size-4">
        <div class="like-count text-sm text-rose-500"><?= $data['likeCount'] ?></div>
      <?php else: ?>
        <img src="/images/unlike-icon.svg" alt="unlike-icon" class="size-4">
        <div class="like-count text-sm text-slate-400"><?= $data['likeCount'] ?></div>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <img src="/images/unlike-icon.svg" alt="unlike-icon" class="size-4">
  <?php endif; ?>
  </button>
  <div class="like-error-message hidden py-2 text-xs text-center text-red-600 bg-red-100 rounded-lg"></div>
</form>