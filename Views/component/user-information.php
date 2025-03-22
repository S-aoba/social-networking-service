<?php
    $imagePath = $imagePath === null ? 'https://picsum.photos/200/300' : $imagePath;
?>

<div class="p-4">
    <div class="w-full flex">
        <div class="size-10 rounded-full overflow-hidden">
            <img src="<?php echo $imagePath ?>" alt="user-icon" class="w-full h-full object-cover">
        </div>
        <div class="pl-4 flex items-center justify-center">
            <div>
                <p class="text-base font-semibold"><?php echo $username ?></p>
            </div>
        </div>
    </div>
</div>