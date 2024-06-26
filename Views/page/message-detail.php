<div class="hidden lg:col-span-2 lg:flex lg:flex-col lg:border-r lg:border-slate-100 min-h-screen h-screen w-full">
  <div class="flex items-center justify-between p-4">
    <p class="text-lg font-bold">メッセージ</p>
    <div class="flex space-x-3 items-center">
      <!-- Messageの設定アイコン　/message/settingsが未実装のためアイコンのみ設置 -->
      <div class="h-8 w-8 cursor-pointer hover:bg-slate-200 rounded-full flex items-center justify-center transition-colors duration-300">
        <img class="h-6 w-6 cursor-pointer" src="/images/gear.svg" alt="設定">
      </div>
      <button id="createConversationIconBtn" type="button" class="h-8 w-8 cursor-pointer hover:bg-slate-200 rounded-full flex items-center justify-center transition-colors duration-300">
        <img class="h-6 w-6 cursor-pointer" src="/images/message-plus.svg" alt="設定">
      </button>
    </div>
  </div>
  <!-- search bar -->
  <div class="relative p-4">
    <input class="w-full pl-10 pr-3 py-3 rounded-full border border-slate-300 placeholder-slate-400" type="search" placeholder="ダイレクトメッセージを検索">
    <img class="absolute h-5 w-5 inset-y-0 left-8 top-1/2 transform -translate-y-1/2" src="/images/search-icon.svg" alt="検索アイコン">
  </div>
  <div class="w-full flex-grow overflow-auto">
    <?php foreach ($data_list as $data) : ?>
      <?php require "Views/component/conversation-article.php" ?>
    <?php endforeach; ?>
  </div>
</div>
<?php require "Views/component/modal/conversation/conversation-create-modal.php" ?>

<!-- Messages -->
<div class="lg:col-span-2 col-span-4 flex flex-col">
  <div class="flex space-x-3 items-center justify-start p-4">
    <img class="w-8 h-8 rounded-full border border-slate-300" src="<?= is_null($another_user_profile->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $another_user_profile->getUploadFullPathOfProfileImage() ?>" alt="Sender">
    <p class="text-sm font-bold"><?= $another_user_profile->getUserName() === null ? '名無しユーザー' : $another_user_profile->getUserName() ?></p>
  </div>
  <?php if (count($messages) > 0) : ?>
    <!-- メッセージ送信機能を追加した後に再度確認 -->
    <div class="flex flex-col p-4 space-y-3 border-b border-slate-100">
      <p class="text-sm font-bold"><?= $another_user_profile->getUserName() ?></p>
      <div class="w-full py-3 flex flex-col items-center justify-center space-y-1">
        <img class="w-8 h-8 rounded-full border border-slate-100" src="<?= is_null($another_user_profile->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $another_user_profile->getUploadFullPathOfProfileImage() ?>" alt="Sender">
        <p class="text-sm font-bold"><?= $another_user_profile->getUserName() === null ? '名無しユーザー' : $another_user_profile->getUserName() ?></p>
        <p class="text-sm text-slate-400"><?= $another_user_profile->getUserId() ?></p>
      </div>
    </div>
  <?php endif; ?>
  <?php require "Views/component/message-article.php" ?>
  <div class="border-t border-slate-100 p-3 relative">
    <form id="messageForm" method="POST">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
      <input type="hidden" name="sender_id" value="<?= $login_user_profile->getUserId() ?>">
      <input type="hidden" name="receiver_id" value="<?= $another_user_profile->getUserId() ?>">
      <input type="hidden" name="conversation_id" value="<?= $conversation->getConversationId() ?>">
      <textarea id="message" name="message_body" class="p-3 resize-none w-full min-h-14 focus:outline-none bg-slate-200 rounded-md" placeholder="新しいメッセージを作成" maxlength="255" required></textarea>
      <button type="submit" class="absolute bottom-6 right-5">
        <img class="size-6" src="/images/message-send.svg" alt="メッセージを送信する">
      </button>
    </form>
  </div>
</div>
<script src="/js/message.js"></script>
<script src="/js/conversation/create-conversation.js"></script>
<script src="/js/conversation/delete-conversation.js"></script>
<script src="/js/conversation/conversation-create-menu.js"></script>
<script src="/js/conversation/conversation-create-modal.js"></script>
<script src="/js/conversation/conversation-delete-modal.js"></script>
<script src="/js/textarea/message.js"></script>
