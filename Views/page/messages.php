<?php include "Views/component/dm/create-conversation-modal.php" ?>
<div class="w-full h-full grid grid-cols-12">
  <?php include "Views/component/banner.php" ?>
  <div class="col-span-4 w-full h-full flex flex-col overflow-auto border-r border-slate-200">

    <!-- header -->
    <?php include "Views/component/dm/header.php" ?>

    <!-- DM Search bar -->
    <?php include "Views/component/dm/search-bar.php" ?>

    <!-- Conversations -->
    <?php if ($conversations !== null): ?>
      <div class="flex-1 py-4">
        <?php foreach ($conversations as $data): ?>
          <?php include "Views/component/dm/conversation-item.php" ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php include "Views/component/dm/dm-message-preview.php" ?>
</div>

<script src="js/compose-conversation-form.js"></script>
<script src="js/open-create-conversation-modal.js"></script>
<script src="js/open-delete-conversation-menu.js"></script>
<script src="js/open-delete-conversation-modal.js"></script>
