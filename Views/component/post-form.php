<?php
    $imagePath = $profile->getImagePath() === null ? '/images/default-icon.png' : $profile->getImagePath();
?>

<div class="w-full h-fit flex border-b border-slate-200">
  <div class="p-5">
    <div class="size-10 rounded-full overflow-hidden">
      <img src="<?php echo $imagePath ?>" alt="user-icon" class="w-full h-full object-cover">
    </div>
  </div>
  <form action="form/post" method="post" class="w-full flex flex-col space-y-4" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
    <input type="hidden" name="parent_post_id" value="">

    <textarea name="content" id="content" class="w-full resize-none p-2 focus:outline-none" placeholder="いまどうしてる？"></textarea>
    <div class="w-full h-full">
      <div id="preview-container"></div>
      <label for="uploads-image" class="hover:cursor-pointer">
        <img src="/images/camera.svg" alt="upload-icon">
        <input type="file" id="uploads-image" name="uploads-image" class="hidden" value="">
      </label>

    </div>
    <div class="w-full flex items-center justify-end my-2 pr-2">
      <button type="submit" class="py-2 px-4 rounded-4xl text-sm bg-gray-500/60 text-white font-semibold hover:bg-slate-800 transition duration-300 focus:bg-slate-800">ポストする</button>
    </form>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function() {
    const uploadsImage = document.getElementById('uploads-image');
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