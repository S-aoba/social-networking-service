<div class="col-span-4 lg:col-span-3 min-h-screen h-screen w-full flex flex-col">
  <div class="p-3 flex justify-between items-center">
    <p class="text-xl font-bold">通知</p>
    <div class="size-6">
      <img src="/images/gear.svg" alt="設定" class="bg-cover">
    </div>
  </div>
  <div class="grid grid-cols-3">
    <div class="col-span-1 text-center py-3 border-b border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors duration-300">
      <span class="p-3 font-semibold border-b-4 border-slate-800">すべて</span>
    </div>
    <div class="col-span-1 text-center py-3 border-b border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors duration-300">
      <span class="p-3 text-slate-400">認証済み</span>
    </div>
    <div class="col-span-1 text-center py-3 border-b border-slate-100 cursor-pointer hover:bg-slate-100 transition-colors duration-300">
      <span class="p-3 text-slate-400">＠ツイート</span>
    </div>
  </div>
  <div class="divide-y divide-slate-100 w-full flex-grow overflow-auto">
    <?php require "Views/component/notification-article.php" ?>
  </div>
</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  Notification Information
</div>
