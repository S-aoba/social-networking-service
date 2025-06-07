<?php
$navigationList = [
    [
        'label' => 'Home',
        'name' => 'ホーム',
        'imagePath' => '/images/home.svg',
        'linkPath' => '/'
    ],
    [
        'label' => 'notification',
        'name' => '通知',
        'imagePath' => '/images/notification.svg',
        'linkPath' => '/notification'
    ],
    [
        'label' => 'message',
        'name' => 'メッセージ',
        'imagePath' => '/images/message.svg',
        'linkPath' => '/messages'
    ],
    [
        'label' => 'profile',
        'name' => 'プロフィール',
        'imagePath' => '/images/profile.svg',
        'linkPath' => "/profile?user={$authUser->getUsername()}"
    ]
];
?>

<div class="col-span-2 w-full h-full flex flex-col border-r border-slate-200">
    <?php include "Views/component/logo.php"; ?>
    <div class="w-full flex-1 py-5 flex flex-col">
        <?php foreach ($navigationList as $item): ?>
            <?php include "Views/component/navigation-item.php" ?>
        <?php endforeach; ?>
    </div>
     <?php include 'Views/component/user-information.php' ?>
</div>
