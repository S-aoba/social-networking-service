<?php
$post_detail_link_path = '/' . $data["post"]->getUserId() . '/status/' . $data["post"]->getId();

$image_link_path = '/profile/' . $data["profile"]->getUserId();

$posted_user_profile_path = is_null($data['profile']->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $data['profile']->getUploadFullPathOfProfileImage();

$login_username = $data['profile']->getUsername();

$login_user_id = $data['profile']->getId();

$diff_time = $data['post']->getTimeStamp()->CalculatePostAge();

$is_user_post = $user && $data['post']->getUserId() === $_SESSION['user_id'] ? true : false;

$data_post_id = $data['post']->getId();

$content = $data['post']->getContent();

$file_type = $data['post']->getFileType();

$file_path = match ($file_type) {
  'image' => $data["post"]->getFilePath(),
  'video' => $data["post"]->getVideoPath(),
  default => ''
}
?>

<div class="relative min-h-fit h-fit w-full flex border-b border-slate-200">
  <a href="<?= $post_detail_link_path ?>" class="absolute top-0 lef-0 w-full h-full"></a>
  <div class="h-full min-w-18 w-20 flex justify-end py-5">
    <img src="<?= htmlspecialchars($posted_user_profile_path) ?>" alt="投稿者のプロフィール画像" class="w-12 h-12 rounded-full border border-slate-200">
  </div>
  <div class="h-full w-full p-5 flex flex-col space-y-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-end space-x-3">
        <p class="font-semibold"><?= htmlspecialchars($login_username) ?></p>
        <div class="text-sm text-slate-400">
          <span>
            @
          </span>
          <span>
            <?= htmlspecialchars($login_user_id) ?>
          </span>
        </div>
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
    <p class="text-sm">
      <?= htmlspecialchars($content) ?>
    </p>
    <?php if ($file_type === 'image') : ?>
      <img src="<?= htmlspecialchars($file_path) ?>" class="object-contain h-full w-full border border-slate-300 rounded-xl">
    <?php endif; ?>
    <?php if ($file_type === 'video') : ?>
      <video src="<?= htmlspecialchars($file_path) ?>" class="object-contain h-full w-full border border-slate-300 rounded-xl" controls>
      </video>
    <?php endif; ?>
    <div class="w-full grid grid-cols-5">
      <div class="col-span-1 flex items-center">
        <button id="replyBtn" type="button" class="group flex items-center cursor-pointer">
          <div class="h-full flex items-center p-2 group-hover:bg-blue-400/30 rounded-full transition-color duration-300 z-40">
            <img class="h-4 w-4" src="/images/comment-icon.svg" alt="コメント数">
          </div>
          <span class="-ml-1"><?= $data['replyCount'] ?></span>
        </button>
      </div>
      <?php require 'Views/component/post-like.php' ?>
    </div>
  </div>
</div>
