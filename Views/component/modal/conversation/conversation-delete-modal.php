<div id="conversationDeleteModal" data-conversation-id="<?= $data['conversation']->getConversationId() ?>" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <div class="bg-white p-8 rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">メッセージを削除してもよろしですか？この操作を取り消すことはできません</h2>
    <div class="w-full py-3 flex space-x-5 justify-center items-center">
      <form id="deleteConversationForm" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
        <input type="hidden" name="conversation_id" value="<?= $data['conversation']->getConversationId() ?>">
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-3 rounded-md transition-colors duration-300">削除</button>
      </form>
      <div>
        <button id="postDeleteCancelBtn" data-conversation-id="<?= $data['conversation']->getConversationId() ?>" type="button" class="border border-slate-300 py-2 px-3 hover:bg-slate-200 rounded-md transition-colors duration-300">キャンセル</button>
      </div>
    </div>
  </div>
</div>
