<div class="col-span-4 lg:col-span-3">
  <!-- トレンド and フォロワー タブ -->
  <!-- TODO:cookieでタブの背景を変える処理を追加するのを忘れないこと -->
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
  <?php if ($user) : ?>
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
  <?php foreach ($data_list as $data) : ?>
    <div class="flex border-b border-slate-300 h-fit w-full p-4 space-x-3">
      <!-- User Information -->
      <div class="flex items-start"> <!-- items-start を追加 -->
        <div class="h-10 w-10 border border-slate-300 rounded-full">
          <img src="<?= $data['profile']->getProfileImage() === null ? '/images/default-icon.svg' : htmlspecialchars($data['profile']->getProfileImage()) ?>" alt="post-user-icon" class="object-cover rounded-full">
        </div>
      </div>
      <div class="flex flex-col flex-grow space-y-4"> <!-- flex-grow を追加 -->
        <div class="flex justify-between items-center"> <!-- items-center を追加 -->
          <div class="flex space-x-3 items-center">
            <p class="font-semibold"><?= htmlspecialchars($data['profile']->getUsername()) ?></p>
            <span class="text-sm text-slate-400"><?= htmlspecialchars($data['profile']->getId()) ?></span>
            <!-- diffメソッドを使う -->
            <span class="text-sm text-slate-400"><?= htmlspecialchars($data['post']->getTimeStamp()->getCreatedAt()) ?></span>
          </div>
          <div class="h-8 w-8 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
            <img class="h-4 w-4" src="/images/menu-icon.svg" alt="編集">
          </div>
        </div>
        <!-- Post Content -->
        <div class="text-sm">
          <?= htmlspecialchars($data['post']->getContent()) ?>
          <?php if (!is_null($data["post"]->getImagePath())) : ?>
            <div class="flex justify-center">
              <img src="<?= htmlspecialchars($data["post"]->getImagePath()) ?>" class="w-96 h-96">
            </div>
          <?php endif; ?>
          <?php if (!is_null($data["post"]->getVideoPath())) : ?>
            <div class="flex justify-center">
              <video src="<?= htmlspecialchars($data["post"]->getVideoPath()) ?>" class="w-96 h-96" controls>
            </div>
          <?php endif; ?>
        </div>
        <!-- Post Information -->
        <!-- TODO:コメントのアイコンが上のコンテントやユーザーネームの左端と微妙に合わないので、後で修正 -->
        <div class="flex items-center space-x-3">
          <!-- Comment Icon -->
          <!-- コメント数を表示する -->
          <div class="h-8 w-8 flex items-center justify-center space-x-1 cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
            <img class="h-4 w-4" src="/images/comment-icon.svg" alt="コメント数">
            <p>1</p>
          </div>
          <!-- PostLike Icon -->
          <!-- TODO:アイコンをホバーした時にbgをアイコン側だけにして、数字には左端に少しかかるくらいにする -->
          <?php if ($data['isLike']) : ?>
            <form action="#" method="POST" id="unlikeForm">
              <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
              <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
              <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
              <button type="submit">
                <div class="h-8 w-8 flex items-center justify-center space-x-1 cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
                  <img class="h-4 w-4" src="/images/post-liked-icon.svg" alt="いいね">
                  <span class="text-[#f0609f]"><?= $data['postLikeCount'][0]["COUNT(*)"] ?></span>
                </div>
              </button>
            </form>
          <?php else : ?>
            <form action="#" method="POST" id="likeForm">
              <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
              <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
              <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
              <input type="hidden" name="post_content" value="<?= $data['post']->getContent() ?>">
              <button type="submit">
                <div class="h-8 w-8 flex items-center justify-center space-x-1 cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
                  <img class="h-4 w-4" src="/images/post-like-icon.svg" alt="いいね">
                  <span><?= $data['postLikeCount'][0]["COUNT(*)"] ?></span>
                </div>
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

</div>
<script src="js/post.js"></script>
<script>
  const trendTab = document.getElementById('trend-tab');
  const followerTab = document.getElementById('follower-tab');

  trendTab.addEventListener('click', () => {
    console.log('Trend');
    // trendをtrueに設定
    document.cookie = 'trend=true';
    // followerをfalseに設定
    document.cookie = 'follower=false';

    location.reload();
  });

  followerTab.addEventListener('click', () => {
    console.log('Follower');
    // followerをtrueに設定
    document.cookie = 'follower=true';
    // trendをfalseに設定
    document.cookie = 'trend=false';

    location.reload();
  });
</script>
