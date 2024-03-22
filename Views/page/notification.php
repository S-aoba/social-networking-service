<div class="bg-gray-100 font-sans">
  <div class="container mx-auto py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-md overflow-hidden">
      <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-800">Notifications</h1>
      </div>
      <div class="divide-y divide-gray-200">
        <?php foreach ($notifications as $notification) : ?>
          <?php if ($notification->getType() == 'like') : ?>
            <!-- Notification Item -->
            <div class="p-4 flex items-start space-x-4">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="Profile Picture">
              </div>
              <div class="flex-grow">
                <h2 class="text-lg font-semibold text-gray-800">John Doe</h2>
                <p class="text-gray-600">Liked your tweet</p>
                <p class="text-gray-500 text-xs">2m ago</p>
              </div>
            </div>
          <?php elseif ($notification->getType() == 'follow') : ?>
            <div class="p-4 flex items-start space-x-4">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="Profile Picture">
              </div>
              <div class="flex-grow">
                <h2 class="text-lg font-semibold text-gray-800">Jane Smith</h2>
                <p class="text-gray-600">Followed you</p>
                <p class="text-gray-500 text-xs">5m ago</p>
              </div>
            </div>
          <?php elseif ($notification->getType() == 'comment') : ?>
            <div class="p-4 flex items-start space-x-4">
              <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="Profile Picture">
              </div>
              <div class="flex-grow">
                <h2 class="text-lg font-semibold text-gray-800">Jane Smith</h2>
                <p class="text-gray-600">Commented on your tweet</p>
                <p class="text-gray-500 text-xs">5m ago</p>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
