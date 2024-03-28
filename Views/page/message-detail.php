<div class="hidden lg:col-span-2 lg:block lg:border-r lg:border-slate-100">
  <div class="flex items-center justify-between p-4">
    <p class="text-lg font-bold">メッセージ</p>
    <div class="flex space-x-3 items-center">
      <!-- Messageの設定アイコン　/message/settingsが未実装のためアイコンのみ設置 -->
      <div class="h-8 w-8 cursor-pointer hover:bg-slate-200 rounded-full flex items-center justify-center trasition-colors duration-300">
        <img class="h-6 w-6 cursor-pointer" src="/images/gear.svg" alt="設定">
      </div>
      <button id="createConversationIconBtn" type="button" class="h-8 w-8 cursor-pointer hover:bg-slate-200 rounded-full flex items-center justify-center trasition-colors duration-300">
        <img class="h-6 w-6 cursor-pointer" src="/images/message-plus.svg" alt="設定">
      </button>

    </div>
  </div>
  <!-- search bar -->
  <div class="relative p-4">
    <input class="w-full pl-10 pr-3 py-3 rounded-full border border-slate-300 placeholder-slate-400" type="search" placeholder="ダイレクトメッセージを検索">
    <img class="absolute h-5 w-5 inset-y-0 left-8 top-1/2 transform -translate-y-1/2" src="/images/search-icon.svg" alt="検索アイコン">
  </div>
  <?php foreach ($data_list as $data) : ?>
    <!-- Conversations -->
    <a href="/message/<?= $data['conversation']->getConversationId() ?>" class="w-full p-4 flex mr-2 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
      <img class="w-12 h-12 rounded-full mr-4 border border-slate-300" src="<?= $data['other_user_profile_image_path'] === null ? '/images/default.svg' : $data['other_user_profile_image_path'] ?>" alt="Sender">
      <div class="w-full flex flex-col">
        <div class="w-full flex items-center justify-between mb-3">
          <div class="flex space-x-3 items-center">
            <h3 class="max-w-60 truncate text-lg font-semibold"><?= $data['other_user_name'] === null ? '名無しユーザー' : $data['other_user_name'] ?></h3>
            <span class="max-w-44 truncate text-sm text-slate-400 mr-1">@<span><?= $data['other_user_id'] ?></span></span>
            <!-- TODO: 更新されたらupdated_atを表示するようににする -->
            <span class="text-sm text-slate-400"><?= $data['conversation']->getDataTimeStamp()->getCreatedAt() ?></span>
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
          <p class="w-80 truncate text-sm text-slate-400 truncate"><?= $data['message'][0]->getMessageBody() ?></p>
        <?php else : ?>
          <p class="text-sm text-slate-400">まだメッセージはありません。</p>
        <?php endif; ?>
      </div>
    </a>
  <?php endforeach; ?>
</div>
<!-- Messages -->
<div class="lg:col-span-2 col-span-4 flex flex-col">
  <div class="flex space-x-3 items-center justify-start p-4">
    <img class="w-8 h-8 rounded-full border border-slate-300" src="<?= $another_user_profile->getProfileImage() ?>" alt="Sender">
    <p class="text-sm font-bold"><?= $another_user_profile->getUserName() === null ? '名無しユーザー' : $another_user_profile->getUserName() ?></p>
  </div>
  <?php if (count($messages) > 0) : ?>
    <!-- メッセージ送信機能を追加した後に再度確認 -->
    <div class="flex flex-col p-4 space-y-3 border-b border-slate-100">
      <p class="text-sm font-bold"><?= $another_user_profile->getUserName() ?></p>
      <div class="w-full py-3 flex flex-col items-center justify-center space-y-1">
        <img class="w-8 h-8 rounded-full border border-slate-100" src="<?= $another_user_profile->getProfileImage() ?>" alt="Sender">
        <p class="text-sm font-bold"><?= $another_user_profile->getUserName() === null ? '名無しユーザー' : $another_user_profile->getUserName() ?></p>
        <p class="text-sm text-slate-400"><?= $another_user_profile->getUserId() ?></p>
      </div>
    </div>
  <?php endif; ?>
  <?php foreach ($messages as $message) : ?>
    <div class="px-2 py-5">
      <?php if ($message->getSenderId() === $_SESSION['user_id']) : ?>
        <!-- Sender Message -->
        <div class="flex items-start space-x-3 items-center">
          <img class="w-8 h-8 rounded-full border border-slate-300" src="<?= $login_user_profile->getProfileImage() ?>" alt="Sender">

          <div class="bg-blue-100 p-3 rounded-lg max-w-96">
            <p class="text-sm text-slate-500 break-words"><?= $message->getMessageBody()  ?></p>
          </div>
        </div>
      <?php else : ?>
        <!-- Receiver Message -->
        <div class="flex space-x-3 items-center justify-end">
          <div class="bg-slate-200 p-3 rounded-lg max-w-96">
            <p class="text-sm text-slate-500 break-words"><?= $message->getMessageBody()  ?></p>
          </div>
          <img class="w-8 h-8 rounded-full ml-4 border border-gray-300" src="<?= $another_user_profile->getProfileImage()  ?>" alt="Receiver">
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
  <div class="border-t border-slate-100 p-3 relative">
    <form id="messageForm" method="POST">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
      <input type="hidden" name="sender_id" value="<?= $login_user_profile->getUserId() ?>">
      <input type="hidden" name="receiver_id" value="<?= $another_user_profile->getUserId() ?>">
      <input type="hidden" name="conversation_id" value="<?= $conversation->getConversationId() ?>">
      <textarea id="textarea" class="w-full h-auto py-3 px-5 bg-slate-200 rounded-md text-sm resize-none" name="message_body" placeholder="新しいメッセージを作成"></textarea>
      <button type="submit" class="absolute bottom-6 right-5">
        <img class="size-6" src="/images/message-send.svg" alt="メッセージを送信する">
      </button>
    </form>
  </div>
</div>
<script src="/js/message.js"></script>

<script>
  //textareaの要素を取得
  let textarea = document.getElementById('textarea');
  //textareaのデフォルトの要素の高さを取得
  let clientHeight = textarea.clientHeight;
  //textareaのinputイベント
  textarea.addEventListener('input', () => {
    //textareaの要素の高さを設定（rows属性で行を指定するなら「px」ではなく「auto」で良いかも！）
    textarea.style.height = clientHeight + 'px';
    //textareaの入力内容の高さを取得
    let scrollHeight = textarea.scrollHeight;
    //textareaの高さに入力内容の高さを設定
    textarea.style.height = scrollHeight + 'px';
  });
</script>
