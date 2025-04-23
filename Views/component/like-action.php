<form method="POST" class="post-form w-full h-full flex items-center">
  <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
  <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
  <button type="submit" name="like-button" class= "z-20 hover:bg-red-100 rounded-full p-2 hover:cursor-pointer transition duration-300">
  <?php if($data['like']): ?>
    <div class="flex items-center space-x-1">
      <img src="/images/like-icon.svg" alt="unlike-icon" class="size-4">
      <div class="text-sm text-rose-500"><?= $data['likeCount'] ?></div>
    </div>
  <?php else: ?>
    <img src="/images/unlike-icon.svg" alt="unlike-icon" class="size-4">
  <?php endif; ?>
  </button>
</form>