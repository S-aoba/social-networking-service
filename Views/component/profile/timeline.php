<?php
$imagepath = $profile->getImagePath() === null ? '/images/default-icon.png' : $profile->getImagePath();
$postCount = $posts === null ? 0 : count($posts);
?>

<div class="col-span-8 w-full h-full flex flex-col">
  <?php include "Views/component/profile/edit-profile-modal.php" ?>
  <div class="w-full flex-1">
    <div class="w-full h-fit p-2">
      <div class="flex space-x-2">
        <div><img src="/images/undo-icon.svg" alt="undo" id="undoButton"></div>
        <div><?= $profile->getUsername() ?></div>
      </div>
      <div><?php echo $postCount ?> 件のポスト</div>
      <div class="flex items-center justify-between">
        <img src="<?php echo $imagepath ?>" alt="user-icon" class="size-32">
        <?php if($loginedUserId === $profile->getUserId()): ?>
          <button id="edit-profile-button" class="p-2 border border-slate-200 text-xs rounded-3xl font-semibold hover:bg-slate-100/70 cursor-pointer">プロフィールを編集</button>
        <?php else: ?>
          <form method="POST" id="follow-form">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
            <input type="hidden" name="following_id" value=2>
            <?php if($isFollow): ?>
              <button class="p-2 border bg-slate-700 text-white border-slate-200 text-xs rounded-3xl font-semibold hover:bg-slate-700/70 cursor-pointer">フォロー中</button>
            <?php else : ?>
              <button id="follow-btn" class="p-2 border border-slate-200 text-xs rounded-3xl font-semibold hover:bg-slate-100/70 cursor-pointer">フォロー</button>
            <?php endif ; ?>
          </form>
        <?php endif; ?>
      </div>
      <div class="flex flex-col space-y-2">
        <div>
          <span class="font-semibold">
            ユーザ名: 
          </span>
          <?php echo $profile->getUsername() ?>
        </div>
        <?php if($profile->getAge() !== null): ?>
          <div>
            <span class="font-semibold">
              年齢: 
            </span>
            <?php echo $profile->getAge() ?>
          </div>
        <?php endif; ?>
        <?php if($profile->getAddress() !== null): ?>
        <div>
          <span class="font-semibold">
            住所: 
          </span>
          <?php echo $profile->getAddress() ?>
        </div>
        <?php endif; ?>
        <?php if($profile->getHobby() !== null): ?>
        <div>
          <span class="font-semibold">
            趣味: 
          </span>
          <?php echo $profile->getHobby() ?>
        </div>
        <?php endif; ?>
        <?php if($profile->getSelfIntroduction() !== null): ?>
        <div>
          <p class="font-semibold">
            自己紹介: 
          </p>
          <?php echo $profile->getSelfIntroduction() ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="flex items-center justify-start space-x-2 text-sm text-slate-600 pt-4 pb-2">
        <div>
          <span class="font-semibold">
            <?php echo $followerCount ?>
          </span> 
          フォロー中
        </div>
        <div>
          <span class="font-semibold">
            <?php echo $followingCount ?>
          </span> 
          フォロワー
        </div>
      </div>
    </div>
    <?php foreach ($posts as $data): ?>
    <?php include "Views/component/profile/article.php" ?>
    <?php endforeach; ?>
   </div>
</div>

<script src="/js/undo.js"></script>
<script src="/js/profile-modal.js"></script>
<script src="/js/like.js"></script>
<script src="/js/follow.js"></script>
