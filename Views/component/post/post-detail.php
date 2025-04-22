<?php
    $imagePath = $currentUser->getImagePath() === null ? '/images/default-icon.png' : $currentUser->getImagePath();
?>

<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <div class="flex items-center gap-x-2 p-2">
    <img src="/images/undo-icon.svg" alt="undo-icon" class="size-5" id="undoButton">
    <span class="font-semibold">ポスト</span>
  </div>
  <div class="w-full h-fit border-b border-slate-200">
    <?php include "Views/component/article.php" ?>
  </div>
  <div class="flex">
    <div class="p-5">
      <div class="size-10 rounded-full overflow-hidden">
        <img src="<?php echo $imagePath ?>" alt="user-icon" class="w-full h-full object-cover">
      </div>
    </div>
    <form action="form/reply" method="post" class="w-full flex flex-col space-y-4" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
      <input type="hidden" name="parent_post_id" value="<?= $data['post']->getId(); ?>">

      <textarea name="content" id="content" class="w-full resize-none p-2 focus:outline-none" placeholder="返信をポスト"></textarea>
      <div class="w-full h-full">
        <div id="preview-container"></div>
        <label for="upload-file" class="hover:cursor-pointer">
          <img src="/images/camera.svg" alt="upload-icon">
          <input type="file" id="upload-file" name="upload-file" class="hidden" value="">
        </label>
    </div>
      <div class="w-full flex items-center justify-end my-2 pr-2">
        <button 
          type="submit" 
          class="py-2 px-4 rounded-4xl text-sm bg-gray-500/60 text-white font-semibold hover:bg-slate-800 transition duration-300 focus:bg-slate-800"
        >
        返信
      </button>
      </div>
    </form>
    <div>
      </div>
    </div>
    <div class="w-full h-fit border-t border-slate-200">
      <?php
        if($replies !== null) {
          foreach ($replies as $data) {
            include "Views/component/article.php";
          }
        }
      ?>
    </div>
</div>
<script src="/js/undo.js"></script>
<script src="/js/like.js"></script>
<script src="/js/open-delete-post-menu.js"></script>
<script src="/js/open-delete-post-modal.js"></script>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    const uploadsImage = document.getElementById('upload-file');
    const previewContainer = document.getElementById('preview-container');

    uploadsImage.addEventListener('change', function(event) {
      const files = event.target.files;
      previewContainer.innerHTML = ''; // Clear previous previews

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.classList.add('w-20', 'h-20', 'object-cover', 'rounded-lg', 'm-2');
          previewContainer.appendChild(img);
        };

        reader.readAsDataURL(file);
      }
    });
  });
</script>