<?php
    $imagePath = $data['postedUser']->getImagePath() === null ? '/images/default-icon.png' : $imagePath;
?>

<div class="relative divide-y divide-slate-200">
  <a href="/post?id=<?= $data['post']->getId() ?>" class="absolute w-full h-full z-10 hover:bg-gray-200/40 transition duration-300"></a>
  <div class="flex">
    <div class="p-5">
      <div class="size-10 rounded-full overflow-hidden">
        <img src="<?php echo $imagePath ?>" alt="posted-user-icon" class="w-full h-full object-cover">
          </div>
      </div>
    <div class="w-full">
      <div class="flex space-x-4 py-2">
        <p class="text-sm font-semibold"><?php echo $data['postedUser']->getUsername()  ?></p>
        <div class="text-gray-400 text-xs flex items-center">
          <?php echo $data['post']->getFormattedCreatedAt(); ?>
        </div>
      </div>
      <div class="py-4">
        <p class="text-sm"><?php echo $data['post']->getContent() ?></p>
      </div>
      
      <div class="w-full py-4 flex items-center justify-start">
        <div class="w-20 h-full flex items-center justify-start space-x-2">
          <img src="/images/comment-icon.svg" alt="comment-icon" class="size-4">
          <?php if($data['replyCount']): ?>
            <p class="text-sm text-gray-400"><?php echo $data['replyCount'] ?></p>
          <?php endif; ?>
        </div>
        <div class="w-20 h-full flex items-center justify-start space-x-2">
          <?php if($data['like']): ?>
            <img src="/images/like-icon.svg" alt="unlike-icon" class="size-4 inline-block">
          <?php else: ?>
            <img src="/images/unlike-icon.svg" alt="unlike-icon" class="size-4">
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>