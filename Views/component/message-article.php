<?php foreach ($messages as $message) : ?>
  <div class="px-2 py-5">
    <?php if ($message->getSenderId() === $_SESSION['user_id']) : ?>
      <!-- Sender Message -->
      <div class="flex items-start space-x-3 items-center">
        <img class="w-8 h-8 rounded-full border border-slate-300" src="<?= is_null($login_user_profile-> getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $login_user_profile-> getUploadFullPathOfProfileImage() ?>" alt="Sender">

        <div class="bg-blue-100 p-3 rounded-lg max-w-96">
          <p class="text-sm text-slate-500 break-words"><?= $message->getMessageBody()  ?></p>
        </div>
      </div>
    <?php else : ?>
      <!-- Receiver Message -->
      <div class="flex space-x-3 items-center justify-end">
        <div class="bg-slate-200 p-3 rounded-lg max-w-96">
          <p class="text-sm text-slate-500 break-words"><?= $message->getMessageBody()  ?></p>
        </div>
        <img class="w-8 h-8 rounded-full ml-4 border border-gray-300" src="<?= is_null($another_user_profile->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $another_user_profile->getUploadFullPathOfProfileImage() ?>" alt="Receiver">
      </div>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
