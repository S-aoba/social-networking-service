<div>
  <div class="w-full flex space-x-5">
    <?php include "Views/component/undo.php" ?>
    <div class="w-full flex flex-col">
      <span class="text-base font-semibold">
        <?= $queryUser->getUsername() ?>
      </span>
      <span class="text-xs text-slate-400 font-mono">
        <?php echo $postCount ?> 件のポスト
      </span>
    </div>
  </div>
  
  <div class="py-2 flex items-end justify-between">
    <img src="<?= $queryUser->getImagePath(); ?>" alt="user-icon" class="size-32 rounded-full">

    <?php if($authUser->getUserId() === $queryUser->getUserId()): ?>
      <button 
          id="edit-profile-button" 
          class="p-2 border border-slate-200 text-xs rounded-3xl font-semibold hover:bg-slate-100/70 cursor-pointer"
      >
      プロフィールを編集
    </button>
    <?php else: ?>
      <form method="POST" id="follow-form">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
        <input type="hidden" name="following_id" value=<?= $queryUser->getUserId(); ?>>
        <?php if($isFollow): ?>
          <button 
            class="px-6 py-2 text-sm bg-white font-mono border border-slate-200 rounded-3xl font-semibold hover:brightness-90 cursor-pointer"
          >
          フォロー中
        </button>
        <?php else : ?>
          <button 
          class="px-6 py-2 text-sm bg-black text-white font-mono border border-slate-200 rounded-3xl font-semibold hover:opacity-70 cursor-pointer"
        >
        フォロー
        </button>
        <?php endif ; ?>
        <div id="follow-error-message" class="hidden my-2 py-2 text-xs text-center text-red-600 bg-red-100 rounded-lg"></div>
      </form>
    <?php endif; ?>
  </div>
</div>