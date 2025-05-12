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
    <div class="relative pt-2 px-4">
      <img src="images/search-icon.svg" alt="search-icon" class="absolute top-5 left-7 size-5">
      <input type="text" class="size-full text-sm border border-slate-200 rounded-3xl py-2.5 pl-8 placeholder-slate-600 focus:outline-none" placeholder="ダイレクトメッセージを検索">
    </div>

    <!-- Conversations -->
    <div class="flex-1 py-4">
      <?php foreach($conversations as $data): ?>
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
      <?php if($directMessages !== null): ?>
        <?php foreach($directMessages as $m): ?>
          <?php if($authUser->getUserId() === $m->getSenderId()): ?>
            <!-- AuthUser -->
            <div class="p-3 flex flex-col items-end justify-center space-y-2">
              <span class="font-semibold"><?= $authUser->getUsername(); ?></span>
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

<script>
  window.addEventListener('DOMContentLoaded', () => {
    const textarea = document.getElementById('content');

    const defaultHeight = '40';

    const autoResize = () => {
    textarea.style.height = '40px';
    const scrollHeight = textarea.scrollHeight;
    textarea.style.height = Math.max(scrollHeight, defaultHeight) + 'px';
  };

    textarea.addEventListener('input', autoResize);
  })
</script>
<script src="js/open-create-conversation-modal.js"></script>