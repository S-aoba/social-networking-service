<?php include "Views/component/dm/create-conversation-modal.php" ?>
<div class="w-full h-full grid grid-cols-12">
  <?php include "Views/component/banner.php" ?>
  <div class="col-span-4 w-full h-full flex flex-col overflow-auto border-r border-slate-200">

    <!-- header -->
    <div class="flex items-center justify-between py-2 px-4">
        <h2 class="text-xl font-semibold">メッセージ</h2>
        <div class="flex">
          <div class="flex items-center justify-center size-8 hover:bg-gray-400/30 rounded-full cursor-not-allowed transition duration-300">
            <img src="images/settings-icon.svg" alt="settings-icon" class="size-5">
          </div>
          <div class="compose-conversation-button flex items-center justify-center size-8 hover:bg-gray-400/30 rounded-full transition duration-300 cursor-pointer">
            <img src="images/compose-icon.svg" alt="compose-icon" class="size-5">
          </div>
        </div>
    </div>

    <!-- DM Search bar -->
    <?php include "Views/component/dm/search-bar.php" ?>

    <!-- Conversations -->
    <div class="flex-1 py-4">
      <?php foreach($conversations as $data): ?>
        <?php include "Views/component/dm/conversation-item.php" ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php include "Views/component/dm/dm-message-preview.php" ?>
</div>

<script src="js/open-create-conversation-modal.js"></script>
<script src="js/compose-conversation-form.js"></script>
