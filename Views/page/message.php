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
          <div class="flex items-center justify-center size-8 hover:bg-gray-400/30 rounded-full cursor-not-allowed transition duration-300">
            <img src="images/compose-icon.svg" alt="compose-icon" class="size-5">
          </div>
        </div>
    </div>
    <!-- DM Search bar -->
    <div class="relative pt-2 px-4">
      <img src="images/search-icon.svg" alt="search-icon" class="absolute top-5 left-7 size-5">
      <input type="text" class="size-full text-sm border border-slate-300 rounded-3xl py-2.5 pl-8 placeholder-slate-600 focus:outline-none" placeholder="ダイレクトメッセージを検索">
    </div>
    <!-- DM Message List -->
      <div class="flex-1 py-4">
        <?php foreach($conversations as $data): ?>
          <!-- DM Message List Item -->
          <a href="/message?id=<?= $data['conversation']->getId(); ?>">
            <div class="flex p-4 cursor-pointer transition duration-300 hover:bg-slate-100">
              <!-- User icon -->
                <div class="w-12 shrink-0">
                  <img src="<?= $data['partner']->getImagePath(); ?>" alt="user-icon" class="size-10 rounded-full">
                </div>
              <!-- User info and message -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-4 text-sm pb-2">
                  <p class="font-semibold"><?= $data['partner']->getUsername(); ?></p>
                  <span class="text-slate-400">
                    <?= $data['directMessage'] === null ? 
                          $data['conversation']->getCreatedAt()
                          :  
                          $data['directMessage']->getCreatedAt()?>
                  </span>
                </div>
                <?php if($data['directMessage'] !== null): ?>
                  <div class="text-sm">
                    <p class="truncate overflow-hidden whitespace-nowrap text-ellipsis">
                      <?= $data['directMessage']->getContent(); ?>
                    </p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
      <!-- Direct Message -->
    </div>
    <div class="col-span-6">
      <div class="px-4 py-2">
        <span class="font-semibold"><?= $data['partner']->getUsername(); ?></span>        
      </div>
      <div class="pb-10 flex flex-col items-center justify-center border-b border-slate-100">
        <div class="flex flex-col items-center justify-center space-y-2">
          <img src="<?= $data['partner']->getImagePath(); ?>" alt="partner-user-icon">
          <span class="font-semibold"><?= $data['partner']->getUsername(); ?></span>
          <p class="text-xs text-slate-600">2021年11月からSNSを利用しています</p>
        </div>
      </div>
      <!-- Direct Messages -->
      <?php if($directMessages !== null): ?>
        <div>
        <?php foreach($directMessages as $m): ?>
            <?php if($authUser->getUserId() === $m->getSenderId()): ?>
              <!-- AuthUser -->
              <div class="p-3 flex flex-col items-end justify-center space-y-2">
                <span class="font-semibold">
                  <?= $authUser->getUsername(); ?>
                </span>
                <div class="w-96 break-all p-4 bg-slate-100 rounded-3xl mb-2">
                  <p><?= $m->getContent(); ?></p>
                </div>
                <span class="text-xs text-slate-400"><?= $m->getCreatedAt(); ?></span>
              </div>
            <?php else: ?>
              <!-- PartnerUser -->
              <div class="w-96 break-all p-3 flex flex-col items-start justify-center space-y-2">
                <span class="font-semibold">
                  <?= $data['partner']->getUsername(); ?>
                </span>
                <div class="p-4 bg-slate-100 rounded-3xl mb-2">
                  <p><?= $m->getContent(); ?></p>
                </div>
                <span class="text-xs text-slate-400"><?= $m->getCreatedAt(); ?></span>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
</div>