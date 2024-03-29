<?php foreach ($data_list as $data) : ?>
  <div class="flex border-b border-slate-300 h-fit w-full p-4 space-x-3">
    <!-- User Information -->
    <div class="flex items-start">
      <div class="h-10 w-10 border border-slate-300 rounded-full">
        <a href="profile/<?= $data['profile']->getUserId() ?>">
          <img src="<?= $data['profile']->getProfileImage() === null ? '/images/default-icon.svg' : htmlspecialchars($data['profile']->getProfileImage()) ?>" alt="post-user-icon" class="object-cover rounded-full">
        </a>
      </div>
    </div>
    <div class="flex flex-col flex-grow space-y-4">
      <div class="flex justify-between items-center">
        <div class="flex space-x-3 items-center">
          <p class="font-semibold"><?= is_null($data['profile']->getUsername()) ? '名無しユーザー' : htmlspecialchars($data['profile']->getUsername()) ?></p>
          <span class="text-sm text-slate-400">
            <span>
              @
            </span>
            <?= htmlspecialchars($data['profile']->getId()) ?>
          </span>
          <span class="text-sm text-slate-400"><?= htmlspecialchars($data['post']->getTimeStamp()->CalculatePostAge()) ?></span>
        </div>
        <?php if ($data['post']->getUserId() === $_SESSION['user_id']) : ?>
          <div id="post-menu" class="relative h-8 w-8 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
            <img class="h-4 w-4" src="/images/menu-icon.svg" alt="編集">
            <div id="menu" class="h-fit bg-white flex flex-col space-y-4 absolute top-5 -left-20 shadow-md border border-slate-300 rounded-md hidden">
              <button id="deleteBtn" type="button" class="w-full p-3 flex items-center text-red-400 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
                <img class="h-6 w-6" src="/images/delete-icon.svg" alt="投稿を削除する">
                <span class="ml-2">削除</span>
              </button>
            </div>
          </div>
          <!-- PostDeleteModal -->
          <div id="postDeleteModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-8 rounded-lg">
              <h2 class="text-2xl font-semibold mb-6">削除してもよろしですか？この操作を取り消すことはできません</h2>
              <div class="w-full h-full flex space-x-3 items-center justify-center rounded-md">
                <form id="deletePostForm" method="POST" action="#">
                  <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
                  <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
                  <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
                  <button type="submit" class="bg-red-500 text-white hover:bg-red-700 py-2 px-3 rounded-md transition-colors duration-300">削除</button>
                </form>
                <div>
                  <button id="postDeleteCancelBtn" type="button" class="border border-slate-300 py-2 px-3 hover:bg-slate-200 rounded-md transition-colors duration-300">キャンセル</button>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
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
