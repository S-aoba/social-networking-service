<!-- 投稿フォーム -->
<div class="bg-white mx-6 p-4 rounded-lg shadow-md">
  <form action="#" method="POST" id="postForm" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <div class="flex items-center">
      <img src="https://via.placeholder.com/40" alt="プロフィール画像" class="w-10 h-10 rounded-full">
      <textarea id="content" name="content" class="p-3 ml-4 resize-none border rounded-md focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200 w-full" rows="3" placeholder="何かつぶやく..."></textarea>
    </div>
    <div class="mt-4 flex justify-end">
      <button id="post" type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">投稿する</button>
    </div>
  </form>
</div>
<!-- タイムライン -->
<div class="flex flex-col space-y-4 m-6">
  <?php foreach ($data_list as $data) : ?>
    <div class="bg-white p-4 rounded-lg shadow-md">
      <div class="flex items-center">
        <img src="https://via.placeholder.com/40" alt="プロフィール画像" class="w-10 h-10 rounded-full">
        <div class="ml-4">
          <p class="text-xs text-gray-500"><?= htmlspecialchars($data["post"]->getTimeStamp()->getCreatedAt()) ?></p>
        </div>
      </div>
      <div class="mt-4">
        <p class="text-lg"><?= htmlspecialchars($data['post']->getContent()) ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<script src="js/post.js"></script>
