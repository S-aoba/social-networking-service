<?php
    $imagePath = $imagePath === null ? '/images/default-icon.png' : $imagePath;
?>

<div class="border-t border-slate-200 divide-y divide-slate-200">
  <div class="flex">
    <div class="p-5">
      <div class="size-10 rounded-full overflow-hidden">
        <img src="<?php echo $imagePath ?>" alt="posted-user-icon" class="w-full h-full object-cover">
          </div>
      </div>
    <div class="p-2">
      <div class="flex space-x-4">
        <p class="text-sm font-semibold"><?php echo $username  ?></p>
        <div class="text-gray-400 text-xs flex items-center">
          <?php echo $data->getFormattedCreatedAt(); ?>
        </div>
      </div>
      <div class="mt-2">
        <p class="text-sm"><?php echo $data->getContent() ?></p>
      </div>
    </div>
  </div>
</div>