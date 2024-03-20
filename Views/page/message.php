<div class="bg-gray-100 font-sans">
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Conversation List -->
      <div class="col-span-1 md:col-span-3">
        <div class="bg-white rounded-lg shadow-md overflow-y-auto max-h-screen">
          <ul class="divide-y divide-gray-200">
            <?php foreach ($data_list as $data) : ?>
              <!-- Conversation Item -->
              <li class="px-6 py-4 transition duration-300 ease-in-out hover:bg-gray-100 hover:cursor-pointer">
                <a href="/message/<?= $data['conversation']->getConversationId() ?>">
                  <div class="flex items-center">
                    <!-- 相手のアイコン -->
                    <img class="w-12 h-12 rounded-full mr-4 border border-gray-400" src="<?= $data['other_user_profile_image_path'] ?>" alt="Sender">
                    <div class="flex flex-col space-y-3">
                      <div class="flex space-x-2 items-center">
                        <h3 class="text-lg font-semibold text-gray-800"><?= $data['other_user_name'] ?></h3>
                        <span class="text-sm text-gray-400 mr-1">@<span><?= $data['other_user_id'] ?></span></span>
                        <!-- TODO: 更新されたらupdated_atを表示するようににする -->
                        <span class="text-sm text-gray-400"><?= $data['conversation']->getDataTimeStamp()->getCreatedAt() ?></span>
                      </div>
                      <?php if (count($data['message']) >= 1) : ?>
                        <p class="text-sm text-gray-600"><?= $data['message'][0]->getMessageBody() ?></p>
                      <?php else : ?>
                        <p class="text-sm text-gray-600">まだメッセージはありません。</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <!-- Messages -->
      <div class="hidden md:block col-span-21">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Header -->
          <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">メッセージを選択</h3>
          </div>

          <button class="px-2 py-1 bg-blue-500 text-white rounded-lg m-3 text-sm hover:bg-blue-700">新しいメッセージ</button>
        </div>
      </div>
    </div>
  </div>
</div>
