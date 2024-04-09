<div class="col-span-1 flex item-center">
  <?php if ($data['isLike']) : ?>
    <form action="#" method="POST" id="unlikeForm">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
      <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
      <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
      <button type="submit" class="group flex items-center cursor-pointer">
        <div class="h-full flex items-center p-2 group-hover:bg-[#f0609f]/30 rounded-full transition-color duration-300 z-40">
          <img class="h-4 w-4" src="/images/post-liked-icon.svg" alt="いいね">
        </div>
        <span class="-ml-1 text-[#f0609f]"><?= $data['postLikeCount'][0]["COUNT(*)"] ?></span>
      </button>
    </form>
  <?php else : ?>
    <form action="#" method="POST" id="likeForm">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
      <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
      <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
      <input type="hidden" name="post_content" value="<?= $data['post']->getContent() ?>">
      <button type="submit" class="group flex items-center cursor-pointer">
        <div class="h-full flex items-center p-2 group-hover:bg-[#f0609f]/30 rounded-full transition-color duration-300 z-40">
          <img class="h-4 w-4" src="/images/post-like-icon.svg" alt="いいね">
        </div>
        <span class="-ml-1"><?= $data['postLikeCount'][0]["COUNT(*)"] ?></span>
      </button>
    </form>
  <?php endif; ?>
</div>
