<div class="col-span-4 lg:col-span-2 flex flex-col lg:border-r lg:border-slate-100">
  <!-- top -->
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
    <input class="w-full pl-10 pr-3 py-3 rounded-full border border-slate-300 placeholder-slate-400" type="text" placeholder="ダイレクトメッセージを検索">
    <img class="absolute h-5 w-5 inset-y-0 left-8 top-1/2 transform -translate-y-1/2" src="/images/search-icon.svg" alt="検索アイコン">
  </div>
  <?php require "Views/component/conversation-article.php" ?>
</div>

<?php require "Views/component/conversation-modal.php" ?>

<div class="lg:col-span-2 hidden lg:block lg:flex lg:flex-col lg:items-center lg:justify-center">
  <div>
    <h2 class="text-2xl font-bold mb-2">メッセージを選択</h2>
    <p class="text-sm text-slate-400 mb-4">既存の会話から選択したり、新しい会話を開始したりできます。</p>
    <button id="createConversationBtn" class="px-2 py-2 bg-slate-500 text-white rounded-lg text-sm hover:bg-slate-700">新しいメッセージ</button>
  </div>
</div>

<script src="/js/create-conversation.js"></script>
<script src="/js/delete-conversation.js"></script>
<script src="/js/conversation-create-menu.js"></script>
<script src="/js/conversation-create-modal.js"></script>
<script src="/js/conversation-delete-modal.js"></script>
