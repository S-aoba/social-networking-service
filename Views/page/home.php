<!-- 投稿フォーム -->
<div class="bg-white mx-6 p-4 rounded-lg shadow-md">
  <form action="#" method="POST" id="postForm" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <div class="flex items-center">
      <img src="<?= htmlspecialchars($login_user_profile_image_path) ?>" alt="プロフィール画像" class="w-10 h-10 rounded-full border border-blue-800">
      <textarea id="content" name="content" class="p-3 ml-4 resize-none border rounded-md focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200 w-full" rows="3" placeholder="何かつぶやく..." maxlength="255" required></textarea>
    </div>
    <div class="mt-3">
      <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif, .mp4">
    </div>
    <div class="mt-3 max-h-96 w-full flex justify-center">
      <img id="preview-image" src="" class="object-cover max-h-96">
      <video id="preview-video" src="">
      </video>
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
        <!-- hrefにユーザー名を入れる (profile/{username}) -->
        <a href="profile/<?= htmlspecialchars($data["profile"]->getUsername()) ?>">
          <img src="<?= htmlspecialchars($data["profile"]->getProfileImage()) ?>" alt="プロフィール画像" class="w-10 h-10 rounded-full border border-blue-800">
        </a>
        <div class="ml-4 w-full">
          <div class="flex items-center justify-between py-2">
            <p class="text-lg font-bold"><?= htmlspecialchars($data["profile"]->getUsername()) ?></p>
            <?php if ($data['post']->getUserId() === $_SESSION['user_id']) : ?>
              <form id="deletePostForm" method="post" action="#">
                <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
                <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
                <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded-md">削除</button>
              </form>
            <?php endif; ?>
          </div>
          <p class="text-xs text-gray-500"><?= htmlspecialchars($data["post"]->getTimeStamp()->getCreatedAt()) ?></p>
        </div>
      </div>
      <div class="mt-4">
        <p class="text-lg"><?= htmlspecialchars($data['post']->getContent()) ?></p>
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
      <div class="mt-2 border-t border-gray-300 py-3 flex justify-start items-center">
        <!-- unLike -->
        <?php if ($data['isLike']) : ?>
          <form action="#" method="POST" id="unlikeForm">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
            <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
            <button class="group inline-block text-lg text-pink-500">
              <span class="inline-flex items-center justify-center h-10 w-10 rounded-full">
                <span class="transition-colors duration-300 group-hover:border-pink-500 group-hover:bg-pink-100 group-hover:text-pink-500 rounded-full px-2 py-1">
                  ♡
                </span>
              </span>
              <span class="transition-colors duration-300 group-hover:text-pink-500 -ml-3">
                <?= $data['postLikeCount'][0]["COUNT(*)"] ?>
              </span>
            </button>

          </form>
        <?php else : ?>
          <!-- like -->
          <form action="#" method="POST" id="likeForm">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
            <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
            <button id="likeBtn" type="submit" class="group inline-block text-lg">
              <span class="inline-flex items-center justify-center h-10 w-10 rounded-full">
                <span class="transition-colors duration-300 group-hover:border-pink-500 group-hover:bg-pink-100 group-hover:text-pink-500 rounded-full px-2 py-1">
                  ♡
                </span>
              </span>
              <span class="transition-colors duration-300 group-hover:text-pink-500 -ml-3">
                <?= $data['postLikeCount'][0]["COUNT(*)"] ?>
              </span>
            </button>
          </form>
        <?php endif; ?>
      </div>
      <div class="flex flex-col space-y-4 border-t border-gray-300 pt-5">
        <!-- 返信フォーム -->
        <form action="#" method="POST" id="replyForm">
          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
          <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
          <div class="flex ">
            <img src="<?= htmlspecialchars($login_user_profile_image_path) ?>" alt="プロフィール画像" class="w-10 h-10 rounded-full border border-blue-800">
            <textarea placeholder="返信をポスト" name="reply_content" id="reply_content" cols="30" rows="10" class="p-3 ml-4 resize-none border rounded-md focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-200 w-full h-20" maxlength="255"></textarea>
          </div>
          <div class="mt-4 flex justify-end">
            <button id="reply" type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">返信</button>
          </div>
        </form>
        <!-- 返信リスト -->
        <h3 class="text-lg font-bold text-start text-blue-800">--返信--</h3>
        <div id="reply-List">
          <!-- 個別の返信 -->
          <div class="p-3 border-t border-b border-gray-300 w-full flex">
            <!-- TODO:返信したユーザーのプロフィール画像に変更すること。今はとりあえずUIを確認したいのでログインユーザーの画像を挟んでいる -->
            <img src="<?= htmlspecialchars($login_user_profile_image_path) ?>" alt="プロフィール画像" class="w-10 h-10 rounded-full border border-blue-800">
            <p class="ml-2">毎日のように笑顔で過ごしましょう。小さな幸せを見つけ、大切な人たちと楽しい時間を過ごしましょう。困難な時でも希望を持ち、前向きに挑戦しましょう。自分自身を信じ、努力と忍耐を持って目標に向かって進みましょう。明日への希望を胸に、心豊かな日々を送りましょう。
            </p>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<script src="js/post.js"></script>
