<div class="col-span-4 lg:col-span-3 flex flex-col">
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
  <div class="divide-y divide-slate-100">
    <?php foreach ($data_list as $data) : ?>
      <?php if ($data['notification']->getType() == 'like') : ?>
        <!-- Notification Item -->
        <div class="p-4 flex items-start space-x-4">
          <div class="flex-shrink-0 flex space-x-3 items-center">
            <img class="size-6" src="/images/post-liked-icon.svg" alt="いいね">
            <img class="size-10 rounded-full border border-gray-400" src="<?= $data['profile']->getProfileImage() ?>" alt="Profile Picture">
          </div>
          <div class="flex-grow">
            <p class="text-slate-400"><?= $data['profile']->getUsername() ?>さんがあなたの投稿にいいねをしました</p>
            <?php if (!is_null($data['notification']->getContent())) : ?>
              <p class="text-sm mt-3"><?= $data['notification']->getContent() ?></p>
            <?php endif; ?>
            <p class="text-slate-400 text-xs">
              <!-- 作成日と現在時刻の差を取得 -->
              <?= $data['notification']->diff() ?>
            </p>
          </div>
        </div>
      <?php elseif ($data['notification']->getType() == 'follow') : ?>
        <div class="p-4 flex items-start space-x-4">
          <div class="flex-shrink-0 flex space-x-3 items-center">
            <img class="size-8" src="/images/follow.svg" alt="フォロー">
            <img class="size-10 rounded-full border border-gray-400" src="<?= $data['profile']->getProfileImage() ?>" alt="Profile Picture">
          </div>
          <div class="flex-grow">
            <p class="text-sm">
              <span class="font-bold">
                <?= is_null($data['profile']->getUsername()) ? '名無しユーザー' : $data['profile']->getUsername() ?>
              </span>
              <span>さんにフォローされました</span>
            </p>
            <p class="text-slate-400 text-xs">
              <!-- 作成日と現在時刻の差を取得 -->
              <?= $data['notification']->diff() ?>
            </p>
          </div>
        </div>
      <?php elseif ($data['notification']->getType() == 'comment') : ?>
        <div class="p-4 flex items-start space-x-4">
          <div class="flex-shrink-0">
            <img class="h-10 w-10 rounded-full border border-gray-400" src="<?= $data['profile']->getProfileImage() ?>" alt="Profile Picture">
          </div>
          <div class="flex-grow">
            <h2 class="text-lg font-semibold"><?= is_null($data['profile']->getUsername()) ? '名無しユーザー' : $data['profile']->getUsername() ?></h2>
            <p class="text-slate-400"><?= is_null($data['profile']->getUsername()) ? '名無しユーザー' : $data['profile']->getUsername() ?>さんがあなたの投稿にコメントしました</p>
            <p class="text-slate-400 text-xs">
              <!-- 作成日と現在時刻の差を取得 -->
              <?= $data['notification']->diff() ?>
            </p>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  Notification Information
</div>
