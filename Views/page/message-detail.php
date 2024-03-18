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
                <!-- 相手のアイコン -->
                <img class="w-12 h-12 rounded-full mr-4 border border-gray-400" src="<?= $data['p1_profile_image_path'] ?>" alt="Sender">
                <div class="flex flex-col space-y-3">
                  <div class="flex space-x-2 items-center">
                    <h3 class="text-lg font-semibold text-gray-800">相手のユーザーネーム</h3>
                    <span class="text-sm text-gray-400">@相手のID</span>
                    <span class="text-sm text-gray-400">最新のメッセージの送信時間</span>
                  </div>
                  <p class="text-sm text-gray-600">メッセージの一番新しいものが表示される</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Messages -->
      <div class="hidden col-span-2 md:flex">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Header -->
          <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">メッセージを選択</h3>
          </div>

          <!-- Messages -->
          <div class="px-6 py-4">
            <!-- Sender Message -->
            <div class="flex items-start mb-4">
              <img class="w-8 h-8 rounded-full mr-4" src="https://randomuser.me/api/portraits/women/47.jpg" alt="Sender">
              <div class="bg-blue-100 p-3 rounded-lg">
                <p class="text-sm text-gray-600">Hi there! How are you?</p>
              </div>
            </div>

            <!-- Receiver Message -->
            <div class="flex items-start justify-end mb-4">
              <div class="bg-gray-200 p-3 rounded-lg">
                <p class="text-sm text-gray-600">Hey! I'm good. How about you?</p>
              </div>
              <img class="w-8 h-8 rounded-full ml-4" src="https://randomuser.me/api/portraits/men/81.jpg" alt="Receiver">
            </div>
            <!-- Add more message threads here -->
          </div>

          <!-- Message Input -->
          <div class="bg-gray-100 px-6 py-4 flex items-center">
            <input type="text" placeholder="Type a message..." class="w-full bg-gray-200 focus:outline-none border-none rounded-full py-2 px-4">
            <button class="ml-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-full">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
