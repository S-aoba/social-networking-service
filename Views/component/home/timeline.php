<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <?php include "Views/component/home/tab.php" ?>
  <?php include "Views/component/post-form.php" ?>

  <?php foreach ($followerPosts as $data): ?>
    <?php include "Views/component/article.php" ?>
  <?php endforeach; ?>
   
</div>

<script src="/js/like.js"></script>
<script src="/js/open-delete-post-menu.js"></script>
<script src="/js/open-delete-post-modal.js"></script>