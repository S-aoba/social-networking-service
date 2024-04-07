<div class="col-span-4 lg:col-span-3 min-h-screen h-screen w-full flex flex-col">
  <?php if (count($data) < 1) : ?>
    <div class="w-full h-full flex items-center justify-center text-2xl font-bold">
      <p>この投稿は削除されました。</p>
    </div>
  <?php else : ?>
    <?php require 'Views/component/post-article.php' ?>
    <?php require 'Views/component/form/create-reply-form.php' ?>
    <div class="w-full py-4 flex-grow flex flex-col overflow-y-auto">
      <?php if (!is_null($replies)) : ?>
        <?php foreach ($replies as $reply) : ?>
          <?php require 'Views/component/reply-article.php' ?>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endif; ?>
    </div>
</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  post detail information
</div>
<script src="/js/post/post-edit-menu.js"></script>
<script src="/js/post/post-delete-modal.js"></script>
<script src="/js/post/delete-post.js"></script>
<script src="/js/reply/reply-edit-menu.js"></script>
<script src="/js/reply/reply-delete-modal.js"></script>
<script src="/js/reply/delete-reply.js"></script>
