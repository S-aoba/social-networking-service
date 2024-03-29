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
          <?= $data['notification']->getTimeStamp()->CalculatePostAge() ?>
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
          <?= $data['notification']->getTimeStamp()->CalculatePostAge() ?>
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
          <?= $data['notification']->getTimeStamp()->CalculatePostAge() ?>
        </p>
      </div>
    </div>
  <?php endif; ?>
<?php endforeach; ?>
