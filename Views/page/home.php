<div class="col-span-4 lg:col-span-3">
  <!-- トレンド and フォロワー タブ -->
  <?php if ($user) : ?>
    <div class="grid grid-cols-2 border-t border-b border-slate-200 bg-white font-bold">
      <div id="trend-tab" class="h-full py-3 span-col-1 text-center transition-colors duration-300 cursor-pointer hover:bg-slate-100 <?php echo ($_COOKIE['trend'] == 'true') ? 'bg-gray-300/50 hover:bg-gray-300/50 cursor-pointer' : 'hover:bg-gray-300/50 hover:cursor-pointer'; ?>">
        <p>
          トレンド
        </p>
      </div>
      <div id="follower-tab" class="h-full py-3 span-col-1 text-center transition-colors duration-300 cursor-pointer hover:bg-slate-100  <?php echo ($_COOKIE['trend'] == 'true') ? 'hover:bg-gray-300/50 hover:cursor-pointer' : 'bg-gray-300/50 hover:bg-gray-300/50 cursor-pointer'; ?>">
        <p>
          フォロワー
        </p>
      </div>
    </div>
    <!-- 投稿フォーム -->
    <div class="h-fit p-6 border-b border-slate-200">
      <form action="#" method="POST" id="postForm" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
        <div class="flex h-full">
          <div class="h-full px-3">
            <!-- TODO: プロフィール画像の取得方法を変えたらnullの場合の処理を追加する -->
            <img src="<?= htmlspecialchars($login_user_profile_image_path) ?>" alt="プロフィール画像" class="w-10 h-10 rounded-full border border-slate-300">
          </div>
          <div class="w-full flex flex-col ml-4">
            <textarea id="content" name="content" class="p-3 resize-none border rounded-md focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200 w-full" rows="3" placeholder="いまどうしてる?" maxlength="255" required></textarea>
            <label for="image" class="mt-5 inline-block text-white border border-slate-300 rounded-md cursor-pointer hover:bg-slate-200 h-10 w-10 p-2 transition-colors duration-300">
              <img src="/images/upload-icon.svg" alt="upload-icon" class="object-fit w-full h-full">
            </label>
            <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif, .mp4" class="hidden">
            <div id="preview" class="mt-3 max-h-96 w-full flex justify-center">
            </div>
            <div class="mt-4 flex justify-end">
              <button id="post" type="submit" class="px-4 py-2 border border-slate-300 rounded-md hover:bg-slate-200 transition-colors duration-300">投稿する</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  <?php endif; ?>
  <?php require 'Views/component/post-article.php' ?>
</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  Home Information
</div>

<script src="js/post.js"></script>
<script src="js/preview.js"></script>
<script src="js/post-like.js"></script>
<script src="js/reply.js"></script>
<script src="js/presentation-tab.js"></script>
