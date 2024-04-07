<div class="col-span-4 lg:col-span-3 min-h-screen h-screen w-full flex flex-col">
  <!-- トレンド and フォロワー タブ -->
  <?php if ($user) : ?>
    <div class="grid grid-cols-2 min-h-14 h-14 w-full border-b border-slate-200 font-bold font-sans text-sm">
      <div id="trend-tab" class="col-span-1 h-full flex items-center justify-center transition-colors duration-300 cursor-pointer hover:bg-slate-100">
        <span class="h-full flex items-center px-3 <?= $presentationTab === 'trend' ? 'border-b-4 border-slate-600 relative top-0.5' : 'text-slate-400' ?>">
          トレンド
        </span>
      </div>
      <div id="follower-tab" class="col-span-1 h-full flex items-center justify-center transition-colors duration-300 cursor-pointer hover:bg-slate-100">
        <span class="h-full flex items-center px-3 <?= $presentationTab === 'follower' ? 'border-b-4 border-slate-600 relative top-0.5' : 'text-slate-400' ?>">
          フォロワー
        </span>
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
