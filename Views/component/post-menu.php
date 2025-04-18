<div class="relative inline-block text-left z-20">
  <?php include "Views/component/delete-post-modal.php" ?>
  <div>
    <button type="button" class="menu-button"  aria-expanded="true" aria-haspopup="true">
      <img src="/images/menu-icon.svg" alt="post-menu-icon" class="size-6">
    </button>
  </div>

  <div class="hidden absolute z-200 mt-2 w-56 origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
    <div class="py-1" role="none">
      <button type="button" class="delete-post-button py-2 px-3">削除</button>
    </div>
  </div>
</div>