<div class="w-full h-full grid grid-cols-12">
  <?php include "Views/component/banner.php" ?>

  <!-- Center -->
  <div id="center" class="col-span-8 w-full h-full flex flex-col overflow-auto">
    <div id="notification-header" class="px-4 py-2 border-b border-slate-200">
      <span class="text-lg font-semibold">通知</span>
    </div>

    <div id="notification-item-list" class="divide-y divide-slate-200">

    <!-- クリックするとLink先に飛べるようにする -->

    <!-- Follow -->
    <?php include "Views/component/notification/follow.php" ?>

    <!-- Like -->
    <?php include "Views/component/notification/like.php" ?>

    <!-- Reply -->
    <?php include "Views/component/notification/reply.php" ?>

    </div>
  </div>

  <?php include "Views/component/sidebar.php" ?>
</div>