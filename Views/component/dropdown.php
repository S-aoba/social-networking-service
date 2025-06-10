<div class="relative inline-block text-left">
  <div>
    <button 
      id="user-info-menu-button"
      role="user-info-menu-button"
      type="button" 
      class="inline-flex w-full justify-center gap-x-1.5 rounded-2xl bg-white px-3 py-2 text-sm font-semibold focus:ring-0 text-gray-900 hover:bg-slate-100 hover:cursor-pointer transition duration-300" 
      aria-expanded="true" 
      aria-haspopup="true"
    >
      <div class="flex items-center justify-center space-x-2">
        <img src="<?= $authUser->getImagePath() ?>" alt="user-icon" class="size-8 rounded-full">
        <span class="text-xs font-semibold"><?= $authUser->getUsername() ?></span>
        <svg 
          class="-mr-1 size-5 text-gray-400" 
          viewBox="0 0 20 20" 
          fill="currentColor" 
          aria-hidden="true" 
          data-slot="icon"
        >
          <path 
            fill-rule="evenodd" 
            d="M14.78 11.78a.75.75 0 0 1-1.06 0L10 8.06l-3.72 3.72a.75.75 0 1 1-1.06-1.06l4.25-4.25a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06z" 
            clip-rule="evenodd" 
          />
        </svg>
      </div>
    </button>
  </div>

  <div
       id="user-info-menu" 
       class="hidden absolute left-0 bottom-30 z-10 w-56 py-2 border border-slate-200 rounded-2xl  bg-white shadow-lg ring-slate-200 focus:outline-hidden" 
       role="user-info-menu" 
       aria-orientation="vertical" 
       aria-labelledby="user-info-menu-button" 
       tabindex="-1"
  >
    <div role="none">
      <div class="w-full py-2 cursor-not-allowed pl-4 hover:bg-slate-100 transition duration-300">
        <p class="text-sm font-semibold">既存のアカウントを追加</p>
      </div>
      <div class="hover:bg-slate-100 transition duration-300">
        <form method="POST" action="logout" role="logout-form">
          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
          <div class="flex items-center justify-start space-x-2 pl-4">
            <img src="/images/logout.svg" alt="logout-icon" class="size-5 cursor-pointer">
            <button 
              type="submit" 
              class="block w-full py-2 text-left text-sm text-gray-900 font-semibold cursor-pointer" 
              role="menuitem" 
              tabindex="-1"
            >
            Log out
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="js/open-user-info-dropdown.js"></script>
