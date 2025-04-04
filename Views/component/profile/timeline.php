<?php
$imagepath = $imagepath === null ? '/images/default-icon.png' : $imagepath;
$postCount = count($posts);
?>

<div class="col-span-8 w-full h-full flex flex-col">
  <div class="w-full flex-1">
    <div class="w-full h-fit p-2">
      <div class="flex space-x-2">
        <div><img src="/images/undo-icon.svg" alt="undo"></div>
        <div>aoba</div>
      </div>
      <div><?php echo $postCount ?> 件のポスト</div>
      <div class="flex items-center justify-between">
        <img src="<?php echo $imagepath ?>" alt="user-icon" class="size-32">
        <button class="p-2 border border-slate-200 text-xs rounded-3xl font-semibold hover:bg-slate-100/70 cursor-pointer">プロフィールを編集</button>
      </div>
      <div class="flex flex-col space-y-2">
        <div>
          <span class="font-semibold">
            ユーザ名: 
          </span>
          <?php echo $username ?>
        </div>
        <?php if($age !== null): ?>
          <div>
            <span class="font-semibold">
              年齢: 
            </span>
            33
          </div>
        <?php endif; ?>
        <?php if($address !== null): ?>
        <div>
          <span class="font-semibold">
            住所: 
          </span>
          Osaka
        </div>
        <?php endif; ?>
        <?php if($hobby !== null): ?>
        <div>
          <span class="font-semibold">
            趣味: 
          </span>
          クレーンゲーム
        </div>
        <?php endif; ?>
        <?php if($selfIntroduction !== null): ?>
        <div>
          <p class="font-semibold">
            自己紹介: 
          </p>
        はじめまして! aoba です。  
        フロントエンド・バックエンドの開発をしています。最近はPHPとReactにハマっています。  
        趣味は映画鑑賞とランニング。好きな映画は「インターステラー」です！  
        いろんな人と交流できたら嬉しいです。よろしくお願いします！
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