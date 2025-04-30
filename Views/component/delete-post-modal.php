<div 
  class="delete-post-modal relative hidden cursor-default" 
  aria-labelledby="modal-title" 
  role="dialog" 
  aria-modal="true"
>
  <div class="fixed inset-0 bg-gray-100 opacity-45 brightness-90" aria-hidden="true"></div>
  
  <div class="fixed inset-0 w-screen">
    <div class="flex min-h-full items-center justify-center">
      <div class="max-w-80 w-full p-7 bg-white rounded-2xl">
        <?php include "Views/component/delete-post-form.php" ?>
      </div>
    </div>
  </div>
</div>