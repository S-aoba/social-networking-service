<div class="col-span-4 lg:col-span-3 min-h-screen h-screen w-full flex flex-col"">
  <!-- トレンド and フォロワー タブ -->
  <?php if ($user) : ?>
    <div class=" grid grid-cols-2 border-t border-b border-slate-200 bg-white font-bold">
  <div id="trend-tab" class="h-full py-3 span-col-1 text-center transition-colors duration-300 cursor-pointer hover:bg-slate-100 <?php echo ($presentationTab === 'trend') ? 'bg-gray-300/50 hover:bg-gray-300/50 cursor-pointer' : 'hover:bg-gray-300/50 hover:cursor-pointer'; ?>">
    <p>
      トレンド
    </p>
  </div>
  <div id="follower-tab" class="h-full py-3 span-col-1 text-center transition-colors duration-300 cursor-pointer hover:bg-slate-100  <?php echo ($presentationTab === 'follower') ?  'bg-gray-300/50 hover:bg-gray-300/50 cursor-pointer' : 'hover:bg-gray-300/50 hover:cursor-pointer'; ?>">
    <p>
      フォロワー
    </p>
  </div>
</div>
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
