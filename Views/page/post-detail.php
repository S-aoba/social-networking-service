<div class="col-span-4 lg:col-span-3">
  <?php if (count($data) < 1) : ?>
    <div class="w-full h-full flex items-center justify-center text-2xl font-bold">
      <p>この投稿は削除されました。</p>
    </div>
  <?php else : ?>
    <?php require 'Views/component/post-article.php' ?>
  <?php endif; ?>
</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  post detail information
</div>
<script src="/js/post/post-edit-menu.js"></script>
<script src="/js/post/post-delete-modal.js"></script>
<script src="/js/post/single-delete-post.js"></script>
