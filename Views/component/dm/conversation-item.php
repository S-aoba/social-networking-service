<div class="relative flex p-4 cursor-pointer transition duration-300 hover:bg-slate-100">
  <a 
    href="/message?id=<?= $data['conversation']->getId(); ?>"
    class="absolute inset-0 size-full z-10"
  ></a>

  <!-- User icon -->
  <div class="w-12 shrink-0">
    <img src="<?= $data['partner']->getImagePath(); ?>" alt="user-icon" class="size-10 rounded-full">
  </div>

  <!-- User info and message -->
  <div class="flex-1 min-w-0">
    <div class="flex items-center justify-between pb-2">
      <div class="flex items-center space-x-4 text-sm">
        <p class="font-semibold"><?= $data['partner']->getUsername(); ?></p>
        <span class="text-slate-400">
          <?= $data['directMessage'] === null ?
                $data['conversation']->getCreatedAt()
                :
                $data['directMessage']->getCreatedAt()?>
        </span>
      </div>

      <?php if ($data['conversation']->getUser1Id() === $authUser->getUserId()): ?>
        <?php include "Views/component/dm/conversation-menu-action.php" ?>
      <?php endif; ?>
      
    </div>

    <?php if ($data['directMessage'] !== null): ?>
      <div class="text-sm">
        <p class="truncate overflow-hidden whitespace-nowrap text-ellipsis">
          <?= $data['directMessage']->getContent(); ?>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>