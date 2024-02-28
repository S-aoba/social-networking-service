
    <!-- 投稿フォーム -->
    <div class="bg-white p-4 rounded-lg shadow-md">
      <form action="#" method="post" id="post-form">
        <div class="flex items-center">
          <img src="https://via.placeholder.com/40" alt="プロフィール画像" class="w-10 h-10 rounded-full">
          <textarea id="post" class="p-3 ml-4 resize-none border rounded-md focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200 w-full" rows="3" placeholder="何かつぶやく..."></textarea>
        </div>
        <div class="mt-4 flex justify-end">
          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">投稿する</button>
        </div>
      </form>
    </div>
    <!-- タイムライン -->
    <div class="flex flex-col space-y-4 mt-4">
      <!-- ダミーツイート -->
      <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="flex items-center">
          <img src="https://via.placeholder.com/40" alt="プロフィール画像" class="w-10 h-10 rounded-full">
          <div class="ml-4">
            <h2 class="text-lg font-bold">ユーザー名</h2>
            <p class="text-gray-600">@ユーザー名</p>
          </div>
        </div>
        <p class="mt-2">これはダミーツイートです。Twitter風のUIを作成しています。</p>
      </div>
      <!-- 他のツイート -->
      <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="flex items-center">
          <img src="https://via.placeholder.com/40" alt="プロフィール画像" class="w-10 h-10 rounded-full">
          <div class="ml-4">
            <h2 class="text-lg font-bold">ユーザー名</h2>
            <p class="text-gray-600">@ユーザー名</p>
          </div>
        </div>
        <p class="mt-2">これもダミーツイートです。タイムライン風に表示されています。</p>
      </div>
    </div>

<script src="js/post.js"></script>
