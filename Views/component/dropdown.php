<div class="relative inline-block text-left">
  <div>
    <button  type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-200 ring-inset hover:bg-gray-50" id="menu-button" aria-expanded="true" aria-haspopup="true">
      <div class="flex items-center justify-center space-x-2">
        <img src="<?php echo $imagePath ?>" alt="user-icon" class="size-8">
        <span class="text-xs font-semibold"><?php echo $username ?></span>
        <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
          <path fill-rule="evenodd" d="M14.78 11.78a.75.75 0 0 1-1.06 0L10 8.06l-3.72 3.72a.75.75 0 1 1-1.06-1.06l4.25-4.25a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06z" clip-rule="evenodd" />
        </svg>
      </div>
    </button>
  </div>

  <!--
    Dropdown menu, show/hide based on menu state.

    Entering: "transition ease-out duration-100"
      From: "transform opacity-0 scale-95"
      To: "transform opacity-100 scale-100"
    Leaving: "transition ease-in duration-75"
      From: "transform opacity-100 scale-100"
      To: "transform opacity-0 scale-95"
  -->
  <div class="hidden absolute left-0 bottom-30 z-10 mt-2 w-56 origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
    <div class="py-1" role="none">
      <form method="POST" action="logout" role="logout" class="hover:bg-gray-50">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
        <div class="flex items-center justify-start space-x-2 pl-2">
          <img src="/images/logout.svg" alt="logout-icon" class="size-5">
          <button type="submit" class="block w-full py-2 text-left text-sm text-gray-700" role="menuitem" tabindex="-1" id="menu-item-3">Log out</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const button = document.getElementById('menu-button');
  const menu = document.querySelector('[role="menu"]');

  button.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });

  document.addEventListener('click', (event) => {
    if (!button.contains(event.target) && !menu.contains(event.target)) {
      menu.classList.add('hidden');
    }
  });
</script>
