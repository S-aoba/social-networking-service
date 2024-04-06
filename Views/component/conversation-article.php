<?php foreach ($data_list as $data) : ?>
  <!-- Conversations -->
  <div class="w-full p-4 flex mr-2 hover:bg-slate-100 cursor-pointer transition-colors duration-300 relative">
    <a a href="/message/<?= $data['conversation']->getConversationId() ?>" class="absolute top-0 left-0  w-full h-full"></a>
    <img class="w-12 h-12 rounded-full mr-4 border border-slate-300" src="<?= is_null($data['receiver']->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $data['receiver']->getUploadFullPathOfProfileImage() ?>" alt="receiver_profile_image">
    <div class="w-full flex flex-col">
      <div class="w-full flex items-center justify-between mb-3">
        <div class="flex space-x-3 items-center">
          <h3 class="max-w-60 truncate text-lg font-semibold text-gray-800"><?= is_null($data['receiver']->getUsername()) ? '名無しユーザー' : $data['receiver']->getUsername() ?></h3>
          <span class="max-w-44 truncate text-sm text-gray-400 mr-1">@<span><?= $data['receiver']->getUserId() ?></span></span>
          <!-- TODO: 更新されたらupdated_atを表示するようににする -->
          <span class="text-sm text-gray-400"><?= $data['conversation']->getDataTimeStamp()->CalculatePostAge() ?></span>
        </div>
        <div id="conversation-menu-icon" class="relative h-8 w-8 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full hover:z-40">
          <img class="h-4 w-4" src="/images/menu-icon.svg" alt="メッセージを削除する">
          <div id="conversation-menu" class="h-fit bg-white flex flex-col space-y-4 absolute top-5 -left-20 shadow-md border border-slate-300 rounded-md hidden z-40">
            <button id="deleteBtn" type="button" class="w-full p-3 flex items-center text-red-400 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
              <img class="h-6 w-6" src="/images/delete-icon.svg" alt="投稿を削除する">
              <span class="ml-2">削除</span>
            </button>
          </div>
        </div>
        <?php require "Views/component/modal/conversation/conversation-delete-modal.php" ?>
      </div>
      <?php if (count($data['latest_message']) >= 1) : ?>
        <p class="w-80 truncate text-sm text-slate-400 truncate"><?= $data['latest_message'][0]->getMessageBody() ?></p>
      <?php else : ?>
        <p class="text-sm text-slate-400">まだメッセージはありません。</p>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
