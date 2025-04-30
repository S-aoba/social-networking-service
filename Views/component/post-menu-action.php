<div class="relative w-full flex items-center justify-end z-20 cursor-pointer">
  <?php include "Views/component/delete-post-modal.php" ?>
  <div class="flex items-center justify-center size-7 hover:bg-slate-100 rounded-full transition duration-300">
    <button 
      type="button" 
      class="menu-button" 
      aria-expanded="true" 
      aria-haspopup="true"
    >
      <img 
        src="/images/menu-icon.svg" 
        alt="post-menu-icon" 
        class="size-5 cursor-pointer focus:ring-0"
      >
    </button>
  </div>

  <div 
    class="hidden absolute top-10 right-0 z-20 w-56 origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden" 
    role="menu" 
    aria-orientation="vertical" 
    aria-labelledby="menu-button" 
    tabindex="-1"
  >
    <div class="py-2">
      <button 
        type="button" 
        class="delete-post-button w-full flex items-center text-start py-2 px-3 hover:bg-slate-100 cursor-pointer text-[#EA3323] font-semibold font-serif text-sm"
      >
      <img 
        src="/images/delete-post-icon.svg" 
        alt="delete-post-icon" 
        class="size-5 mr-2"
      >
      削除
    </button>
    </div>
  </div>
</div>