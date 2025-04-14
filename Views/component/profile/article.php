<?php
    $imagePath = $imagePath === null ? '/images/default-icon.png' : $imagePath;
?>

<div class="relative border-t border-slate-200 divide-y divide-slate-200">
  <a href="/post?id=<?= $data['post']->getId() ?>" class="absolute w-full h-full z-10 hover:bg-gray-200/40 transition duration-300"></a>
  <div class="flex">
    <div class="p-5">
      <div class="size-10 rounded-full overflow-hidden">
        <a href="<?php echo '/profile?user=' . $username; ?>"">
          <img src="<?php echo $imagePath ?>" alt="posted-user-icon" class="w-full h-full object-cover">
        </a>
      </div>
    </div>
    <div class="w-full">
      <div class="flex space-x-4">
        <p class="text-sm font-semibold"><?php echo $username  ?></p>
        <div class="text-gray-400 text-xs flex items-center">
          <?php echo $data['post']->getFormattedCreatedAt(); ?>
        </div>
      </div>
      <div class="py-4">
        <p class="text-sm"><?php echo $data['post']->getContent() ?></p>
      </div>
      
      <div class="w-full h-fit flex items-center justify-start">
        <div class="w-20 h-full flex items-center justify-start space-x-2">
          <img src="/images/comment-icon.svg" alt="comment-icon" class="size-4">
          <?php if($data['replyCount']): ?>
            <p class="text-sm text-gray-400"><?php echo $data['replyCount'] ?></p>
          <?php endif; ?>
        </div>
        <div class="w-full h-full flex items-center justify-start space-x-2 py-5">
          <div class="flex items-center justify-start space-x-1">
            <form method="POST" class="post-form w-full h-full flex items-center">
              <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
              <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
              <button type="submit" name="like-button" class= "z-20 hover:bg-red-100 rounded-full p-2 hover:cursor-pointer transition duration-300">
                  <?php if($data['like']): ?>
                  <div class="flex items-center space-x-2">
                    <img src="/images/like-icon.svg" alt="unlike-icon" class="size-4">
                    <div class="text-sm text-gray-400"><?= $data['likeCount'] ?></div>
                  </div>
                  <?php else: ?>
                  <img src="/images/unlike-icon.svg" alt="unlike-icon" class="size-4">
                  <?php endif; ?>
                </button>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>