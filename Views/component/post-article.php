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
        <?php if ($user && $data['post']->getUserId() === $_SESSION['user_id']) : ?>
          <div id="post-menu" class="relative h-8 w-8 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
            <img class="h-4 w-4" src="/images/menu-icon.svg" alt="編集">
            <div id="menu" class="h-fit bg-white flex flex-col space-y-4 absolute top-5 -left-20 shadow-md border border-slate-300 rounded-md hidden">
              <button id="deleteBtn" type="button" class="w-full p-3 flex items-center text-red-400 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
                <img class="h-6 w-6" src="/images/delete-icon.svg" alt="投稿を削除する">
                <span class="ml-2">削除</span>
              </button>
            </div>
          </div>
          <?php require 'Views/component/post-delete-modal.php' ?>
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
        <?php require 'Views/component/post-like.php' ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
