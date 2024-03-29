<div class="col-span-4 lg:col-span-3 flex flex-col w-full h-full">
  <div class="flex items-center p-3 border-b">
    <div class="size-10 cursor-pointer hover:bg-slate-100 flex items-center justify-center rounded-full">
      <img src="/images/undo.svg" alt="戻る" class="size-8">
    </div>
    <div class="ml-5 flex flex-col items-start justify-center space-y-1">
      <h1 class="text-2xl font-bold">
        <?= is_null($profile->getUsername()) ? '名無しユーザー' : htmlspecialchars($profile->getUsername()) ?>
      </h1>
      <span class="text-slate-500 text-sm">
        451. <span class="text-slate-500 text-sm">万件のポスト</span>
      </span>

    </div>
  </div>
  <!-- Header -->
  <!-- でもまだ実装していないので、とりあえず固定の画像をおく -->
  <div class="h-46 relative">
    <img class="bg-cover w-full bg-blue-400" src="/images/test.jpeg" alt="バナー画像">
    <img src="<?= htmlspecialchars($profile->getProfileImage()) ?>" alt="プロフィール画像" class="size-40 absolute -bottom-20 left-5 border-8 border-white rounded-full bg-white">
  </div>
  <div class="w-full p-5 flex justify-end">
    <?php if ($is_self_profile) : ?>
      <button id="follow" type="submit" class="py-2 px-3 border border-slate-300 rounded-full font-semibold text-sm text-center cursor-pointer hover:bg-slate-100 transition-colors duration-300">プロフィールを編集</button>
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
        30
        <span class="text-slate-400 font-normal">
          フォロー中
        </span>
      </span>
      <span class="font-bold">
        30
        <span class="text-slate-400 font-normal">
          フォロワー
        </span>
      </span>
    </div>
  </div>
  <!-- tab: ロジックは未実装 -->
  <div class="grid grid-cols-6 border-b border-slate-100">
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-3 transition-colors duration-300">
      <span class="font-bold border-b-4 border-slate-700 pb-3">
        ポスト
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-3 transition-colors duration-300">
      <span class="pb-3">
        返信
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-3 transition-colors duration-300">
      <span class="pb-3">
        ハイライト
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-3 transition-colors duration-300">
      <span class="pb-3">
        記事
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-3 transition-colors duration-300">
      <span class="pb-3">
        メディア
      </span>
    </div>
    <div class="col-span-1 cursor-pointer hover:bg-slate-100 text-center p-3 transition-colors duration-300">
      <span class="pb-3">
        いいね
      </span>
    </div>
  </div>
  <!-- userの投稿の一覧 -->
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
            <span class="text-sm text-slate-400"><?= htmlspecialchars($data['profile']->getId()) ?></span>
            <!-- diffメソッドを使う -->
            <span class="text-sm text-slate-400"><?= htmlspecialchars($data['post']->getTimeStamp()->getCreatedAt()) ?></span>
          </div>
          <!-- TODO: 現状は削除以外のアクションがないので自身の投稿のみに表示する。 -->
          <!-- 　　　　削除以外のアクションが増えたら、削除のボタンのみを非表示にするように変更する -->
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
                <form id="deletePostForm" method="POST">
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

<div class="lg:col-span-1 hidden lg:block h-full pr-4 md:pr-6 bg-orange-400">
  Profile Information
</div>
<!-- user_idのUserのProfile情報, ログインユーザーがそのuser_idのユーザーをフォローしているかどうかの情報 -->
<!-- user_idのユーザーの投稿一覧情報 -->
<script src="/js/post-like.js"></script>
<script src="/js/reply.js"></script>
<script src="/js/profile-post-delete.js"></script>
<script src="/js/follow.js"></script>
