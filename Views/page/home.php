<div class="col-span-4 lg:col-span-3 min-h-screen h-screen w-full flex flex-col">
  <!-- トレンド and フォロワー タブ -->
  <?php if ($user) : ?>
    <?php require 'Views/component/presentation-tab.php' ?>
    <?php require 'Views/component/form/create-post-form.php' ?>
  <?php endif; ?>
  <div class="w-full flex-grow overflow-auto">
    <?php foreach ($data_list as $data) : ?>
      <?php require 'Views/component/post-article.php' ?>
    <?php endforeach; ?>
  </div>

</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  Home Information
</div>
<script src="/js/post/post-edit-menu.js"></script>
<script src="/js/post/post-delete-modal.js"></script>
<script src="/js/post/delete-post.js"></script>
<script src="/js/presentation-tab.js"></script>
<script src="/js/preview.js"></script>
<script src="/js/post-like/post-like.js"></script>
