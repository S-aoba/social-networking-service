<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <?php include "Views/component/home/tab.php" ?>
  <?php include "Views/component/post-form.php" ?>

  <?php if(count($followerPosts) > 0): ?>
    <div class="divide-y divide-slate-200">
      <?php foreach ($followerPosts as $data): ?>
        <?php include "Views/component/article.php" ?>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div>
      投稿はありません
    </div>
  <?php endif; ?>
   
</div>

<script src="/js/like.js"></script>
<script src="/js/open-delete-post-menu.js"></script>
<script src="/js/open-delete-post-modal.js"></script>