<div class="col-span-4 lg:col-span-3 flex flex-col">
  <!-- top -->
  <div class="flex items-center justify-between p-4">
    <p class="text-lg font-bold">メッセージ</p>
    <div>
      <!-- Messageの設定アイコン　/message/settingsが未実装のためアイコンのみ設置 -->
      <img class="h-6 w-6 cursor-pointer" src="/images/gear.svg" alt="設定">
    </div>
  </div>
  <!-- search bar -->
  <div class="relative p-4">
    <input class="w-full pl-10 pr-3 py-3 rounded-full border border-slate-300 placeholder-slate-400" type="text" placeholder="ダイレクトメッセージを検索">
    <img class="absolute h-5 w-5 inset-y-0 left-8 top-1/2 transform -translate-y-1/2" src="/images/search-icon.svg" alt="検索アイコン">
  </div>
  <?php foreach ($data_list as $data) : ?>
    <!-- Conversations -->
    <div class="w-full p-4 flex mr-2 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
      <img class="w-12 h-12 rounded-full mr-4 border border-slate-300" src="<?= $data['other_user_profile_image_path'] === null ? '/images/default.svg' : $data['other_user_profile_image_path'] ?>" alt="Sender">
      <div class="flex flex-col">
        <div class="flex space-x-3 items-center justify-start mb-3">
          <h3 class="max-w-60 truncate text-lg font-semibold text-gray-800"><?= $data['other_user_name'] === null ? '名無しユーザー' : $data['other_user_name'] ?></h3>
          <span class="max-w-44 truncate text-sm text-gray-400 mr-1">@<span><?= $data['other_user_id'] ?></span></span>
          <!-- TODO: 更新されたらupdated_atを表示するようににする -->
          <span class="text-sm text-gray-400"><?= $data['conversation']->getDataTimeStamp()->getCreatedAt() ?></span>
        </div>
        <?php if (count($data['message']) >= 1) : ?>
          <p class="w-96 truncate text-sm text-slate-400 truncate"><?= $data['message'][0]->getMessageBody() ?></p>
        <?php else : ?>
          <p class="text-sm text-slate-400">まだメッセージはありません。</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Conversation Modal -->
<div id="conversationModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <!-- Modal Content -->
  <div class="bg-white p-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">新しいメッセージを送信</h2>
    <div class="w-full h-full">
      <?php if (count($data_list) < 1) : ?>
        <p>現在あなたをフォローしているユーザーはいません</p>
      <?php else : ?>
        <?php foreach ($followee_users as $followee_user) : ?>
          <form method="POST" class="newConversationForm">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
            <input type="hidden" name="participant1_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="participant2_id" value="<?= $followee_user->getUserId() ?>">
            <button type="submit" class="w-full flex items-center z-10 hover:cursor-pointer hover:bg-gray-200 p-2">
              <img class="w-12 h-12 rounded-full mr-4 border border-gray-400" src="<?= $followee_user->getProfileImage() ?>" alt="フォロワー">
              <div>
                <p class="text-base text-gray-500"><?= $followee_user->getUsername() ?></p>
                <p class="text-base text-gray-500"><?= $followee_user->getUserId() ?></p>
              </div>
            </button>
            <hr class="border-gray-300 mb-6 mt-3">
          </form>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="js/conversation.js"></script>
<script src="js/conversation-modal.js"></script>
