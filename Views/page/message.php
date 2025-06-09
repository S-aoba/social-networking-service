<?php include "Views/component/dm/create-conversation-modal.php" ?>
<div class="w-full h-full grid grid-cols-12">
  <?php include "Views/component/banner.php" ?>
  <div class="col-span-4 w-full h-full flex flex-col overflow-auto border-r border-slate-200">

    <!-- header -->
    <?php include "Views/component/dm/header.php" ?>

    <!-- DM Search bar -->
    <?php include "Views/component/dm/search-bar.php" ?>

    <!-- Conversations -->
    <div class="flex-1 py-4">
      <?php foreach ($conversations as $data): ?>
        <?php include "Views/component/dm/conversation-item.php" ?>
      <?php endforeach; ?>
    </div>
  </div>
      
    <!-- Direct Messages -->
    <div class="relative col-span-6 w-full flex flex-col h-screen border-r border-slate-200">

    <!-- Partner User Information -->
    <div class="px-4 py-2 font-semibold">
      <?= $partner->getUsername(); ?>
    </div>
    <div class="pb-10 flex flex-col items-center justify-center border-b border-slate-200">
      <div class="flex flex-col items-center justify-center space-y-2">
        <img src="<?= $partner->getImagePath(); ?>" alt="partner-user-icon" class="size-20 rounded-full">
        <span class="font-semibold"><?= $partner->getUsername(); ?></span>
      </div>
    </div>

    <!-- Direct Message Content -->
    <div class="flex-1 overflow-y-auto px-4 py-2 space-y-4">
      <?php if ($directMessages !== null): ?>
        <?php foreach ($directMessages as $m): ?>
          <?php if ($authUser->getUserId() === $m->getSenderId()): ?>
            <!-- AuthUser -->
            <div class="p-3 flex flex-col items-end justify-center space-y-2">
              <span class="font-semibold"><?= $authUser->getUsername(); ?></span>
              <?php if ($m->getImagePath() !== null): ?>
                <div>
                  <img src="<?= $m->getImagePath(); ?>" alt="dm-image" class="size-40 rounded-2xl">
                </div>
              <?php endif; ?>
              <div class="max-w-96 break-all p-4 bg-slate-100 rounded-3xl mb-2">
                <p><?= $m->getContent(); ?></p>
              </div>
              <span class="text-xs text-slate-400"><?= $m->getCreatedAt(); ?></span>
            </div>
          <?php else: ?>
            <!-- PartnerUser -->
            <div class="max-w-96 break-all p-3 flex flex-col items-start justify-center space-y-2">
              <span class="font-semibold"><?= $partner->getUsername(); ?></span>
              <div class="p-4 bg-slate-100 rounded-3xl mb-2">
                <p><?= $m->getContent(); ?></p>
              </div>
              <span class="text-xs text-slate-400"><?= $m->getCreatedAt(); ?></span>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
      </div>

    <!-- Direct Message Form -->
    <?php include "Views/component/dm/dm-create-form.php" ?>
  </div>

</div>

<script src="js/compose-conversation-form.js"></script>
<script src="js/open-create-conversation-modal.js"></script>
<script src="js/open-delete-conversation-menu.js"></script>
<script src="js/open-delete-conversation-modal.js"></script>
<script src="/js/resize-dm-textarea.js"></script>
<script src="js/delete-conversation-form.js"></script>
