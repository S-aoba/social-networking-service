<?php include "Views/component/dm/delete-conversation-modal.php" ?>

<button 
type="button"
class="delete-conversation-button z-20 size-7 flex items-center justify-center hover:bg-sky-200/80 rounded-full transition duration-300"
>
  <img
  src="/images/menu-icon.svg" 
  alt="post-menu-icon" 
  class="size-5 cursor-pointer focus:ring-0"
  >
</button>

<!-- Delete conversation dropdowm menu -->
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
      class="delete-conversation-action-button w-full flex items-center text-start py-2 px-3 hover:bg-slate-100 cursor-pointer text-[#EA3323] font-semibold font-serif text-sm"
      data-target="modal-<?= $data['conversation']->getId(); ?>"
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