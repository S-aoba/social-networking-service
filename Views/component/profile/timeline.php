<?php
$imagepath = $profile->getImagePath() === null ? '/images/default-icon.png' : $profile->getImagePath();
$postCount = $posts === null ? 0 : count($posts);

$userInfoList = [
  'username' => $profile->getUsername(),
  'age' => $profile->getAge(),
  'address' => $profile->getAddress(),
  'hobby' => $profile->getHobby(),
  'selfIntroduction' => $profile->getSelfIntroduction()
];

$followerAndFollowingCountList = [
  'フォロー中' => $followingCount,
  'フォロワー' => $followerCount
];

?>

<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <?php include "Views/component/profile/edit-profile-modal.php" ?>
    <div class="w-full p-2">
      <?php include "Views/component/profile/header.php" ?>
      <div class="py-4 flex flex-col space-y-2">
        <?php foreach($userInfoList as $key => $val): ?>
          <?php include "Views/component/profile/user-info.php" ?>
        <?php endforeach; ?>
      </div>
      <div class="flex items-center justify-start space-x-2">
        <?php foreach($followerAndFollowingCountList as $key => $val): ?>
          <?php include "Views/component/profile/followerAndFollowing.php"; ?>
        <?php endforeach; ?>
      </div>
    </div>
    <?php foreach ($posts as $data): ?>
    <?php include "Views/component/profile/article.php" ?>
    <?php endforeach; ?>
</div>

<script src="/js/undo.js"></script>
<script src="/js/profile-modal.js"></script>
<script src="/js/like.js"></script>
<script src="/js/follow.js"></script>
<script src="/js/open-delete-post-menu.js"></script>
<script src="/js/open-delete-post-modal.js"></script>