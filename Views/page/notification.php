<div class="w-full h-full grid grid-cols-12">
  <?php include "Views/component/banner.php" ?>

  <!-- Center -->
  <div id="center" class="col-span-8 w-full h-full flex flex-col overflow-auto">
    <div id="notification-header" class="px-4 py-2 border-b border-slate-200">
      <span class="text-lg font-semibold">通知</span>
    </div>

    <div id="notification-item-list" class="divide-y divide-slate-200">

    <!-- クリックするとLink先に飛べるようにする -->

    <?php foreach ($notifications as $notification):  ?>
      <?php if($notification->getType() === 'follow'): ?>
        <!-- Follow -->
        <?php include "Views/component/notification/follow.php" ?>
      <?php elseif($notification->getType() === 'like'): ?>
        <!-- Like -->
        <?php include "Views/component/notification/like.php" ?>
      <?php elseif($notification->getType() === 'reply'): ?>
        <!-- Reply -->
        <?php include "Views/component/notification/reply.php" ?>
      <?php endif; ?>
    <?php endforeach; ?>
    </div>
  </div>

  <?php include "Views/component/sidebar.php" ?>
</div>