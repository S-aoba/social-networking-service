<?php
    $imagePath = $data['postedUser']->getImagePath() === null ? '/images/default-icon.png' : $imagePath;
?>

<div class="relative">
  <a 
    href="/post?id=<?= $data['post']->getId() ?>" 
    class="absolute w-full h-full z-10 hover:bg-slate-100 hover:opacity-30 transition duration-300"
  ></a>
  <div class="flex py-2">
    <div id="contributor-avator" class="px-5">
      <div class="relative size-10 rounded-full overflow-hidden">
        <a 
          href="<?= '/profile?user=' . $currentUser->getUsername(); ?>"" 
          class="absolute inset-0 z-20 hover:bg-slate-800/25 transition duration-300"
        >
      </a>
          <img 
            src="<?= $imagePath ?>" 
            alt="posted-user-icon" 
            class="w-full h-full object-cover"
          >
      </div>  
    </div>
    <div class="w-full pl-2 pr-20">
      <div class="w-full flex items-center justify-between">
        <div class="w-full flex items-center space-x-2">
          <p class="text-sm font-semibold">
            <?= $data['postedUser']->getUsername(); ?>
          </p>
          <div class="text-gray-400 text-xs flex items-center">
            <?= $data['post']->getFormattedCreatedAt(); ?>
          </div>
        </div>
        <?php if($currentUser->getUserId() === $data['postedUser']->getUserId()): ?>
          <?php include "Views/component/post-menu-action.php" ?>
        <?php endif ;?>
      </div>
      <div id="post-content" class="w-full py-2 flex flex-col items-start justify-center space-y-4">
        <p class="text-sm"><?php echo $data['post']->getContent() ?></p>
        <?php if($data['post']->getImagePath() !== null): ?>
          <div class="size-full rounded-lg overflow-hidden">
            <img 
              src="<?php echo $data['post']->getImagePath() ?>" 
              alt="post-image" 
              class="size-full object-contain rounded-lg"
            >
          </div>
        <?php endif; ?>
      </div>
      <div id="post-action" class="w-full grid grid-cols-5">
        <div class="grid-cols-1 flex items-center">
          <img 
            src="/images/comment-icon.svg" 
            alt="comment-icon" 
            class="size-4"
          >
          <?php if($data['replyCount']): ?>
            <p class="text-sm text-gray-400">
              <?php echo $data['replyCount'] ?>
            </p>
          <?php endif; ?>
        </div>
        <div class="grid-cols-1">
          <?php include "Views/component/like-action.php" ?>
        </div>
      </div>
    </div>
  </div>
</div>

