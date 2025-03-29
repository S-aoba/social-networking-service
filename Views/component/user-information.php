<?php
    $imagePath = $imagePath === null ? 'https://picsum.photos/200/300' : $imagePath;
?>

<div class="p-4">
    <div class="w-full flex">
        <div class="size-10 rounded-full overflow-hidden">
            <img src="<?php echo $imagePath ?>" alt="user-icon" class="w-full h-full object-cover">
        </div>
        <div class="pl-4 flex flex-col items-start justify-center">
            <div>
                <p class="text-base font-semibold"><?php echo $username ?></p>
            </div>
            <div class="text-sm">
                <span><?php echo $followerCount ?> フォロー</span>
                <span><?php echo $followingCount ?> フォロワー</span>
            </div>
        </div>
    </div>
</div>