<?php
$message = $notification->getData()['message'];
$redirect = $notification->getData()['redirect'];
$imagePath = $notification->getData()['imagePath'];
$content = $notification->getData()['content'];
?>

<form method="POST" class="notification-form">
  <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
  <input type="hidden" name="notification_id" value="<?= $notification->getId(); ?>">
  <div id="notification-item" class="py-2 flex min-h-20 hover:bg-slate-100 transition duration-300 cursor-pointer">
    <div class="w-16 flex justify-end">
      <img src="/images/like-icon.svg" alt="like-icon" class="size-7">
    </div>
    <div class="w-96 pl-2 flex flex-col space-y-2">
      <img src="<?= htmlspecialchars($imagePath) ?>" alt="user-icon" class="size-8">
      <span class="text-sm"><?= htmlspecialchars($message); ?></span>
      <span class="truncate text-xs text-gray-500"><?= htmlspecialchars($content); ?></span>
    </div>
  </div>
</form>