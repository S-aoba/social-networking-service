<div class="bg-gray-100 font-sans">
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:flex md:flex-col gap-4">
      <!-- Conversation List -->
      <div class="col-span-1 md:flex ">
        <div class="bg-white rounded-lg shadow-md overflow-y-auto max-h-screen">
          <ul class="divide-y divide-gray-200">
            <!-- Conversation Item -->
            <li class="px-6 py-4 transition duration-300 ease-in-out hover:bg-gray-100 hover:cursor-pointer">
              <div class="flex items-center">
                <!-- 自分のアイコン -->
                <img class="w-12 h-12 rounded-full mr-4 border border-gray-400" src="<?= $another_user_profile->getProfileImage() ?>" alt="Sender">
                <div class="flex flex-col space-y-3">
                  <div class="flex space-x-2 items-center">
                    <h3 class="text-lg font-semibold text-gray-800"><?= $another_user_profile->getUsername() ?></h3>
                    <span class="text-sm text-gray-400">@
                      <span><?= $another_user_profile->getUserId() ?></span>
                    </span>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Messages -->
      <div class="col-span-2 flex flex-col">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Header -->
          <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">メッセージを選択</h3>
          </div>

          <!-- Messages -->
          <?php foreach ($messages as $message) : ?>
            <div class="px-6 py-4">
              <?php if ($message->getSenderId() === $_SESSION['user_id']) : ?>
                <!-- Sender Message -->
                <div class="flex items-start mb-4">
                  <img class="w-8 h-8 rounded-full mr-4" src="<?= $login_user_profile->getProfileImage() ?>" alt="Sender">
                  <p><?= $login_user_profile->getUserName() ?></p>
                  <div class="bg-blue-100 p-3 rounded-lg">
                    <p class="text-sm text-gray-600"><?= $message->getMessageBody()  ?></p>
                  </div>
                </div>
              <?php else : ?>
                <!-- Receiver Message -->
                <div class="flex items-start justify-end mb-4">
                  <div class="bg-gray-200 p-3 rounded-lg">
                    <p class="text-sm text-gray-600"><?= $message->getMessageBody()  ?></p>
                  </div>
                  <img class="w-8 h-8 rounded-full ml-4 border border-gray-400" src="<?= $another_user_profile->getProfileImage()  ?>" alt="Receiver">
                  <p><?= $another_user_profile->getUserName() ?></p>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
          <!-- Message Input -->
          <form id="messageForm" method="POST" action="#" class="bg-gray-100 px-6 py-4 flex items-center">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
            <input type="hidden" name="sender_id" value="<?= $login_user_profile->getUserId() ?>">
            <input type="hidden" name="receiver_id" value="<?= $another_user_profile->getUserId() ?>">
            <input type="hidden" name="conversation_id" value="<?= $conversation->getConversationId() ?>">
            <input type="text" name="message_body" placeholder="Type a message..." class="w-full bg-gray-200 focus:outline-none border-none rounded-full py-2 px-4">
            <button type="submit" class="ml-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-full">Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/js/message.js"></script>
