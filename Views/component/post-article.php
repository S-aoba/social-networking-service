<?php
$post_detail_link_path = '/' . $data["post"]->getUserId() . '/status/' . $data["post"]->getId();

$image_link_path = '/profile/' . $data["profile"]->getUserId();

$posted_user_profile_path = is_null($data['profile']->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $data['profile']->getUploadFullPathOfProfileImage();

$login_username = $data['profile']->getUsername();

$login_user_id = $data['profile']->getId();

$diff_time = $data['post']->getTimeStamp()->CalculatePostAge();

$is_user_post = $user && $data['post']->getUserId() === $_SESSION['user_id'] ? true : false;

$data_post_id = $data['post']->getId();

$content = $data['post']->getContent()
?>

<div class="flex border-b border-slate-300 h-fit w-full p-4 space-x-3 relative">
  <a href="<?= $post_detail_link_path ?>" class="absolute top-0 left-0 w-full h-full cursor-pointer"></a>
  <!-- User Information -->
  <div class="flex items-start">
    <div class="h-10 w-10 border border-slate-300 rounded-full">
      <a href="<?= $image_link_path ?>" class="z-50">
        <img src="<?= htmlspecialchars($posted_user_profile_path) ?>" alt="post-user-icon" class="h-10 w-10 object-cover rounded-full">
      </a>
    </div>
  </div>
  <div class="flex flex-col flex-grow space-y-4">
    <div class="flex justify-between items-center">
      <div class="flex space-x-3 items-center">
        <p class="font-semibold"><?= htmlspecialchars($login_username) ?></p>
        <span class="text-sm text-slate-400">
          <span>
            @
          </span>
          <span>
            <?= htmlspecialchars($login_user_id) ?>
          </span>
        </span>
        <span class="text-sm text-slate-400"><?= htmlspecialchars($diff_time) ?></span>
      </div>
      <?php if ($is_user_post) : ?>
        <div id="post-menu" class="relative h-8 w-8 flex items-center justify-center cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
          <img class="h-4 w-4" src="/images/menu-icon.svg" alt="編集">
          <div id="menu" class="h-fit bg-white flex flex-col space-y-4 absolute top-5 -left-20 shadow-md border border-slate-300 rounded-md hidden">
            <button id="deleteBtn" data-post-id="<?= $data_post_id ?>" type="button" class="w-full p-3 flex items-center text-red-400 hover:bg-slate-100 cursor-pointer transition-colors duration-300">
              <img class="h-6 w-6" src="/images/delete-icon.svg" alt="投稿を削除する">
              <span class="ml-2">削除</span>
            </button>
          </div>
        </div>
        <?php require 'Views/component/post-delete-modal.php' ?>
      <?php endif; ?>
    </div>
    <!-- Post Content -->
    <div>
      <p class="text-sm pb-5">
        <?= htmlspecialchars($content) ?>
      </p>
      <?php if ($data['post']->getFileType() === 'image' && !is_null($data["post"]->getFilePath())) : ?>
        <div>
          <img src="<?= htmlspecialchars($data["post"]->getFilePath()) ?>" class="w-96 h-96">
        </div>
      <?php endif; ?>
      <?php if ($data['post']->getFileType() === 'video' && !is_null($data["post"]->getFilePath())) : ?>
        <div>
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
        <span><?= $data['replyCount'] ?></span>
      </div>
      <?php require 'Views/component/post-like.php' ?>
    </div>
  </div>
</div>
