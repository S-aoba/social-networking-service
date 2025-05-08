<div id="create-conversation-modal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
          <div class="flex p-4 cursor-pointer transition duration-300 hover:bg-slate-100">
            <!-- User icon -->
              <div class="w-12 shrink-0">
                <img src="images/default-icon.png" alt="user-icon" class="size-10 rounded-full">
              </div>
            <!-- User info -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center space-x-4 text-sm pb-2">
                <p class="font-semibold">Admin</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('create-conversation-modal');
    const closeButton = document.getElementById('close-button');

    closeButton.addEventListener('click', function() {
      modal.classList.add('hidden');
    });
  });

</script>