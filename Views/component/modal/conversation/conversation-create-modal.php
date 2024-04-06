<div id="conversationModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <!-- Modal Content -->
  <div class="w-11/12 lg:w-[600px] bg-white p-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">新しいメッセージ</h2>
    <div class="w-full h-full">
      <?php if (count($followee_users) < 1) : ?>
        <p>現在あなたをフォローしているユーザーはいません</p>
      <?php else : ?>
        <?php foreach ($followee_users as $followee_user) : ?>
          <form method="POST" class="newConversationForm">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
            <input type="hidden" name="participant1_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="participant2_id" value="<?= $followee_user->getUserId() ?>">
            <button type="submit" class="w-full flex items-center z-10 cursor-pointer hover:bg-slate-100 p-2 transition-colors duration-300">
              <img class="w-12 h-12 rounded-full mr-4 border border-slate-300" src="<?= is_null($followee_user->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $followee_user->getUploadFullPathOfProfileImage() ?>" alt="フォロワー">
              <div class="flex space-x-3 items-center">
                <h3 class="max-w-60 truncate text-sm font-semibold text-gray-800"><?= $followee_user->getUsername() === null ? '名無しユーザー' : $followee_user->getUsername() ?></h3>
                <span class="max-w-44 truncate text-sm text-gray-400 mr-1">@<span><?= $followee_user->getUserId() ?></span></span>
              </div>
            </button>
            <hr class="border-gray-300 mb-6">
          </form>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
