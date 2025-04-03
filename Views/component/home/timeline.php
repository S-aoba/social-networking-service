<div class="col-span-8 w-full h-full flex flex-col">
   <?php include "Views/component/home/tab.php" ?>
   <?php include "Views/component/post-form.php" ?>

   <div class="w-full flex-1">
     <?php foreach ($followerPosts as $data): ?>
      <?php include "Views/component/article.php" ?>
     <?php endforeach; ?>
   </div>
</div>