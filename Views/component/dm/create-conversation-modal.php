<div id="create-conversation-modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
  
  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="min-h-full flex items-center justify-center">
      <div class="w-[38rem] h-[45rem] bg-white rounded-3xl">
        <div class="flex items-center">
          <div id="close-button" class="size-12 flex items-center justify-center">
            <div class="size-10 flex items-center justify-center hover:bg-gray-400/50 rounded-full hover:cursor-pointer transition duration-300">
              <img src="/images/close-icon.svg" alt="close-icon" class="size-5">
            </div>
          </div>
          <p class="text-lg font-semibold">新しいメッセージ</p>
        </div>
        <!-- Follower List -->
        <div>
          
        <div id="conversation-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100"></div>

        <?php if ($followers === null): ?>
          <div class="flex flex-col space-y-2 items-center justify-center pt-10">
            <p class="text-2xl font-semibold">
              まだフォロワーがいません
            </p>
            <p class="text-sm text-slate-400">フォローされるとここに表示されます</p>
          </div>
        <?php else: ?>
          <?php foreach ($followers as $data): ?>
            <form class="create-conversation-form flex p-4 cursor-pointer transition duration-300 hover:bg-slate-100">
              <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
              <input type="hidden" name="user1_id" value="<?= $authUser->getUserId(); ?>">
              <input type="hidden" name="user2_id" value="<?= $data->getUserId(); ?>">
              <!-- User icon -->
              <div class="w-12 shrink-0">
                <img src="<?= $data->getImagePath(); ?>" alt="user-icon" class="size-10 rounded-full">
              </div>
              <!-- User info -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-4 text-sm pb-2">
                  <p class="font-semibold"><?= $data->getUsername(); ?></p>
                </div>
              </div>
            </form>
          <?php endforeach ;?>
        <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="js/create-conversation-modal.js"></script>