<div class="bg-gray-100 font-sans">
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Conversation List -->
      <div class="col-span-1 md:col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-y-auto max-h-screen">
          <ul class="divide-y divide-gray-200">
            <?php foreach ($data_list as $data) : ?>
              <!-- Conversation Item -->
              <li class="px-6 py-4 transition duration-300 ease-in-out hover:bg-gray-100 hover:cursor-pointer">
                <div class="flex items-center">
                  <img class="w-12 h-12 rounded-full mr-4" src="<?= $data['p1_profile_image_path'] ?>" alt="Sender">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-800"><?= $data['p1_username'] ?></h3>
                    <p class="text-sm text-gray-600"><?= $data['conversation']->getParticipate1Id() ?></p>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
            <!-- Add more conversation items here -->
          </ul>
        </div>
      </div>

      <!-- Messages -->
      <div class="hidden md:block col-span-2">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Header -->
          <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">Direct Messages</h3>
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
