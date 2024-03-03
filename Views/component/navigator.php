<div class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 flex justify-between items-center p-4 w-full max-w-screen-lg mx-auto">
  <h1 class="text-2xl font-bold text-blue-400">Social Networking Service</h1>
  <?php if ($user) : ?>
    <nav>
      <ul class="flex space-x-4">
        <li><a href="/home" class="text-gray-600 hover:text-blue-400">ホーム</a></li>
        <li><a href="/profile" class="text-gray-600 hover:text-blue-400">プロフィール</a></li>
        <li><a href="#" class="text-gray-600 hover:text-blue-400">通知</a></li>
        <li><a href="#" class="text-gray-600 hover:text-blue-400">メッセージ</a></li>
      </ul>
    </nav>
  <?php endif; ?>
</div>
