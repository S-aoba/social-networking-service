<div class="col-span-4 lg:col-span-2 flex flex-col">
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
      <div class="w-full flex flex-col">
        <div class="w-full flex items-center justify-between mb-3">
          <div class="flex space-x-3 items-center">
            <h3 class="max-w-60 truncate text-lg font-semibold text-gray-800"><?= $data['other_user_name'] === null ? '名無しユーザー' : $data['other_user_name'] ?></h3>
            <span class="max-w-44 truncate text-sm text-gray-400 mr-1">@<span><?= $data['other_user_id'] ?></span></span>
            <!-- TODO: 更新されたらupdated_atを表示するようににする -->
            <span class="text-sm text-gray-400"><?= $data['conversation']->getDataTimeStamp()->getCreatedAt() ?></span>
          </div>
          <div id="conversation-menu-icon" class="relative h-8 w-8 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
            <img class="h-4 w-4" src="/images/menu-icon.svg" alt="メッセージを削除する">
            <div id="conversation-menu" class="h-fit bg-white flex flex-col space-y-4 absolute top-5 -left-20 shadow-md border border-slate-300 rounded-md hidden z-40">
              <button id="deleteBtn" type="button" class="w-full p-3 flex items-center text-red-400 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
                <img class="h-6 w-6" src="/images/delete-icon.svg" alt="投稿を削除する">
                <span class="ml-2">削除</span>
              </button>
            </div>
          </div>
          <!-- Conversation Delete Modal -->
          <div id="conversationDeleteModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-8 rounded-lg">
              <h2 class="text-2xl font-semibold mb-6">メッセージを削除してもよろしですか？この操作を取り消すことはできません</h2>
              <div class="w-full py-3 flex space-x-5 justify-center items-center">
                <form id="deleteConversationForm" method="POST">
                  <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
                  <input type="hidden" name="conversation_id" value="<?= $data['conversation']->getConversationId() ?>">
                  <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-3 rounded-md transition-colors duration-300">削除</button>
                </form>
                <div>
                  <button id="postDeleteCancelBtn" type="button" class="border border-slate-300 py-2 px-3 hover:bg-slate-200 rounded-md transition-colors duration-300">キャンセル</button>
                </div>
              </div>
            </div>
          </div>
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
    <h2 class="text-2xl font-semibold mb-6">新しいメッセージ</h2>
    <div class="w-full h-full">
      <?php if (count($data_list) < 1) : ?>
        <p>現在あなたをフォローしているユーザーはいません</p>
      <?php else : ?>
        <?php foreach ($followee_users as $followee_user) : ?>
          <form method="POST" class="newConversationForm">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
            <input type="hidden" name="participant1_id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="participant2_id" value="<?= $followee_user->getUserId() ?>">
            <button type="submit" class="w-full flex items-center z-10 cursor-pointer hover:bg-slate-100 p-2">
              <img class="w-12 h-12 rounded-full mr-4 border border-slate-300" src="<?= $followee_user->getProfileImage() ?>" alt="フォロワー">
              <div class="flex space-x-3 items-center">
                <h3 class="max-w-60 truncate text-sm font-semibold text-gray-800"><?= $followee_user->getUsername() === null ? '名無しユーザー' : $data['other_user_name'] ?></h3>
                <span class="max-w-44 truncate text-sm text-gray-400 mr-1">@<span><?= $followee_user->getUserId() ?></span></span>
              </div>
            </button>
            <hr class="border-gray-300 mb-6 mt-3">
          </form>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="lg:col-span-2 hidden lg:block lg:flex lg:flex-col lg:items-center lg:justify-center">
  <div>
    <h2 class="text-2xl font-bold mb-2">メッセージを選択</h2>
    <p class="text-sm text-slate-400 mb-4">既存の会話から選択したり、新しい会話を開始したりできます。</p>
    <button id="createConversationBtn" class="px-2 py-2 bg-slate-500 text-white rounded-lg text-sm hover:bg-slate-700">新しいメッセージ</button>
  </div>
</div>


<script src="js/conversation.js"></script>
<script src="js/conversation-modal.js"></script>
