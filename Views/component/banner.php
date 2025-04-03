<?php 
$navigationList = [
    'Home' => '/images/home.svg',
    '通知' => '/images/notification.svg',
    'メッセージ' => '/images/message.svg',
    'プロフィール' => '/images/profile.svg'
];
?>

<div class="col-span-2 w-full h-full flex flex-col border-r border-slate-200">
    <?php include "Views/component/logo.php"; ?>
    <div class="w-full flex-1 py-5 flex flex-col">
        <?php foreach ($navigationList as $label => $iconPath): ?>
            <?php include "Views/component/navigation-item.php" ?>
        <?php endforeach; ?>
    </div>
     <?php include 'Views/component/user-information.php' ?>
</div>
