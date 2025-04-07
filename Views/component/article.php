<?php
    $imagePath = $data['postedUser']->getImagePath() === null ? '/images/default-icon.png' : $imagePath;
?>

<div class="relative border-t border-slate-200 divide-y divide-slate-200">
  <a href="/post?id=<?= $data['post']->getId() ?>" class="absolute w-full h-full z-10 hover:bg-gray-200/40 transition duration-300"></a>
  <div class="flex">
    <div class="p-5">
      <div class="size-10 rounded-full overflow-hidden">
        <img src="<?php echo $imagePath ?>" alt="posted-user-icon" class="w-full h-full object-cover">
          </div>
      </div>
    <div class="p-2">
      <div class="flex space-x-4">
        <p class="text-sm font-semibold"><?php echo $data['postedUser']->getUsername()  ?></p>
        <div class="text-gray-400 text-xs flex items-center">
          <?php echo $data['post']->getFormattedCreatedAt(); ?>
        </div>
      </div>
      <div class="mt-2">
        <p class="text-sm"><?php echo $data['post']->getContent() ?></p>
      </div>
    </div>
  </div>
</div>