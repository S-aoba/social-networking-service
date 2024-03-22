<div class="bg-gray-100 font-sans">
  <div class="container mx-auto py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-md overflow-hidden">
      <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-800">Notifications</h1>
      </div>
      <div class="divide-y divide-gray-200">
        <?php foreach ($data_list as $data) : ?>
          <?php if ($data['notification']->getType() == 'like') : ?>
            <!-- Notification Item -->
            <div class="p-4 flex items-start space-x-4">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full border border-gray-400" src="<?= $data['profile']->getProfileImage() ?>" alt="Profile Picture">
              </div>
              <div class="flex-grow">
                <h2 class="text-lg font-semibold text-gray-800"><?= $data['profile']->getUsername() ?></h2>
                <p class="text-gray-600"><?= $data['profile']->getUsername() ?>さんがあなたの投稿にいいねをしました</p>
                <p class="text-gray-500 text-xs">
                  <!-- 作成日と現在時刻の差を取得 -->
                  <?= $data['notification']->diff() ?>
                </p>
              </div>
            </div>
          <?php elseif ($data['notification']->getType() == 'follow') : ?>
            <div class="p-4 flex items-start space-x-4">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full border border-gray-400" src="<?= $data['profile']->getProfileImage() ?>" alt="Profile Picture">
              </div>
              <div class="flex-grow">
                <h2 class="text-lg font-semibold text-gray-800"><?= $data['profile']->getUsername() ?></h2>
                <p class="text-gray-600"><?= $data['profile']->getUsername() ?>さんがあなたをフォローしました</p>
                <p class="text-gray-500 text-xs">
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
                <h2 class="text-lg font-semibold text-gray-800"><?= $data['profile']->getUsername() ?></h2>
                <p class="text-gray-600"><?= $data['profile']->getUsername() ?>さんがあなたの投稿にコメントしました</p>
                <p class="text-gray-500 text-xs">
                  <!-- 作成日と現在時刻の差を取得 -->
                  <?= $data['notification']->diff() ?>
                </p>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
