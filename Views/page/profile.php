<div class="col-span-4 lg:col-span-3 min-h-screen h-screen w-full flex flex-col">
  <div class="flex items-center p-3 border-b">
    <div class="size-10 cursor-pointer hover:bg-slate-100 flex items-center justify-center rounded-full">
      <img src="/images/undo.svg" alt="戻る" class="size-8">
    </div>
    <div class="ml-5 flex flex-col items-start justify-center space-y-1">
      <h1 class="text-2xl font-bold">
        <?= is_null($profile->getUsername()) ? '名無しユーザー' : htmlspecialchars($profile->getUsername()) ?>
      </h1>
      <span class="text-slate-500 text-sm">
        <?= $post_count ?>
        <span class="text-slate-500 text-sm">件のポスト</span>
      </span>

    </div>
  </div>
  <!-- Header -->
  <div class="relative">
    <?php if (is_null($profile->getUploadFullPathOfHeader())) : ?>
      <div class="h-48 w-full"></div>
    <?php else : ?>
      <img class="object-cover h-48 w-full" src="<?= $profile->getUploadFullPathOfHeader() ?>" alt="ヘッダー画像">
    <?php endif; ?>
    <img src="<?= htmlspecialchars(is_null($profile->getUploadFullPathOfProfileImage()) ? '/images/default-icon.svg' : $profile->getUploadFullPathOfProfileImage()) ?>" alt="プロフィール画像" class="size-40 absolute -bottom-20 left-5 border-8 border-white rounded-full bg-white">
  </div>
  <div class="w-full p-5 flex justify-end">
    <?php if ($is_self_profile) : ?>
      <button id="profileEditBtn" type="submit" class="py-2 px-3 border border-slate-300 rounded-full font-semibold text-sm text-center cursor-pointer hover:bg-slate-100 transition-colors duration-300">プロフィールを編集</button>
      <?php require 'Views/component/modal/profile/profile-edit-modal.php' ?>
    <?php else : ?>
      <?php if ($is_follow === false) : ?>
        <form id="follow-form" method="post">
          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
          <input type="hidden" name="profileId" value="<?= $profile->getUserId() ?>">
          <input type="hidden" name="userId" value="<?= $_SESSION['user_id'] ?>">
          <button id="follow" type="submit" class="py-2 px-3 border border-slate-300 rounded-full font-semibold text-sm text-center cursor-pointer hover:bg-slate-100 transition-colors duration-300">フォロー</button>
        </form>
      <?php else : ?>
        <form id="unfollow-form" method="post">
          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
          <input type="hidden" name="profileId" value="<?= $profile->getUserId() ?>">
          <input type="hidden" name="userId" value="<?= $_SESSION['user_id'] ?>">
          <button id="unfollow" type="submit" class="py-2 px-3 border border-slate-300 rounded-full font-semibold text-sm text-center cursor-pointer hover:bg-rose-100 hover:text-rose-800 hover:border-rose-100 transition-colors duration-300">フォロー中</button>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  <div class="p-3 flex flex-col space-y-1">
    <p class="text-2xl font-bold">
      <?= is_null($profile->getUsername()) ? '名無しユーザー' : htmlspecialchars($profile->getUsername()) ?>
    </p>
    <span class="text-sm text-slate-400">
      @
      <?= htmlspecialchars($profile->getUserId()) ?>
    </span>
    <?php if (!is_null($profile->getSelfIntroduction())) : ?>
      <p>
        <?= htmlspecialchars($profile->getSelfIntroduction()) ?>
      </p>
    <?php endif; ?>
    <!-- フォロー数とフォロワー数: 未実装のためハードコーディング -->
    <div class="flex space-x-2 items-center text-sm">
      <span class="font-bold">
        <?= $follow_count['follow_count'] ?>
        <span class="text-slate-400 font-normal">
          フォロー中
        </span>
      </span>
      <span class="font-bold">
        <?= $follower_count['follower_count'] ?>
        <span class="text-slate-400 font-normal">
          フォロワー
        </span>
      </span>
    </div>
  </div>
  <!-- tab: ロジックは未実装 -->
  <div class="grid grid-cols-6 border-b border-slate-100">
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-2 md:p-3 transition-colors duration-300">
      <span class="font-bold border-b-4 border-slate-700 pb-2 md:pb-3">
        ポスト
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-2 md:p-3 transition-colors duration-300">
      <span class="text-sm pb-2 md:pb-3">
        返信
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-2 md:p-3 transition-colors duration-300">
      <span class="text-sm pb-2 md:pb-3">
        ハイライト
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-2 md:p-3 transition-colors duration-300">
      <span class="text-sm pb-2 md:pb-3">
        記事
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-2 md:p-3 transition-colors duration-300">
      <span class="text-sm pb-2 md:pb-3">
        メディア
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-2 md:p-3 transition-colors duration-300">
      <span class="text-sm pb-2 md:pb-3">
        いいね
      </span>
    </div>
  </div>
  <!-- userの投稿の一覧 -->
  <div class="w-full flex-grow overflow-auto">
    <?php foreach ($data_list as $data) : ?>
      <?php require 'Views/component/post-article.php' ?>
    <?php endforeach; ?>
  </div>
</div>
<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  Profile Information
</div>
<script src="/js/post/post-edit-menu.js"></script>
<script src="/js/post/post-delete-modal.js"></script>
<script src="/js/post/delete-post.js"></script>
