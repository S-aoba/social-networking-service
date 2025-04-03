<?php
    $imagePath = $imagePath === null ? '/images/default-icon.png' : $imagePath;
?>

<div class="max-h-24 h-full flex">
    <div class="flex items-center justify-center">
        <img src="<?php echo $imagePath ?>" alt="user-icon" class="size-10">
    </div>
    <div class="w-fit h-full flex items-center justify-start pl-2">
        <p class="font-semibold"><?php echo $username ?></p>
    </div>
</div>