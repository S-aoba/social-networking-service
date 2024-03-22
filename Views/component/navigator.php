<div class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 flex justify-between items-center p-4 w-full max-w-screen-lg mx-auto">
  <h1 class="text-2xl font-bold text-blue-400">SNS</h1>
  <nav>
    <ul class="flex space-x-4">
      <?php if ($user) : ?>
        <li><a href="/home" class="text-gray-600 hover:text-blue-400">ホーム</a></li>
        <li><a href="/edit/profile" class="text-gray-600 hover:text-blue-400">プロフィール</a></li>
        <li><a href="/notification" class="text-gray-600 hover:text-blue-400">通知</a></li>
        <li><a href="/message" class="text-gray-600 hover:text-blue-400">メッセージ</a></li>
        <?php if ($user) : ?>
          <li><a href="/logout" class="text-gray-600 hover:text-blue-400">ログアウト</a></li>
        <?php endif; ?>
      <?php else : ?>
        <li><a href="/register" class="text-gray-600 hover:text-blue-400">新規登録</a></li>
        <li><a href="/login" class="text-gray-600 hover:text-blue-400">ログイン</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
