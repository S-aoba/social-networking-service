<?php foreach ($data_list as $data) : ?>
  <!-- Conversations -->
  <a href="/message/<?= $data['conversation']->getConversationId() ?>" class="w-full p-4 flex mr-2 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
    <img class="w-12 h-12 rounded-full mr-4 border border-slate-300" src="<?= $data['other_user_profile_image_path'] === null ? '/images/default.svg' : $data['other_user_profile_image_path'] ?>" alt="Sender">
    <div class="w-full flex flex-col">
      <div class="w-full flex items-center justify-between mb-3">
        <div class="flex space-x-3 items-center">
          <h3 class="max-w-60 truncate text-lg font-semibold text-gray-800"><?= $data['other_user_name'] === null ? '名無しユーザー' : $data['other_user_name'] ?></h3>
          <span class="max-w-44 truncate text-sm text-gray-400 mr-1">@<span><?= $data['other_user_id'] ?></span></span>
          <!-- TODO: 更新されたらupdated_atを表示するようににする -->
          <span class="text-sm text-gray-400"><?= $data['conversation']->getDataTimeStamp()->CalculatePostAge() ?></span>
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
