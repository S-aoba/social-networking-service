<?php
    $imagePath = $profile->getImagePath() === null ? '/images/default-icon.png' : $profile->getImagePath();
?>

<div class="w-full h-fit flex border-b border-slate-200 py-4">
  <div role="contributor-icon" class="px-5">
    <div class="size-10 rounded-full overflow-hidden">
      <a href="<?php echo '/profile?user=' . $profile->getUsername(); ?>"" class="z-20 relative hover:opacity-50">
        <img src="<?php echo $imagePath ?>" alt="posted-user-icon" class="w-full h-full object-cover">
      </a>
    </div>
  </div>
  <div class="w-full">
    <form action="form/post" method="post" enctype="multipart/form-data">
      <input 
        type="hidden" 
        name="csrf_token" 
        value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>"
      >
      <input 
        type="hidden" 
        name="parent_post_id" 
        value=''
      >
      <textarea 
        id="content" 
        name="content" 
        class="block p-2 w-full resize-none overflow-hidden focus:outline-none"
        rows="2"
        minlength="1"
        maxlength="144"
        placeholder="いまどうしてる？"
        require
      ></textarea>
      <div class="w-full flex flex-col justify-center items-start">
        <div id="preview-container" class="hidden pr-4 pb-4 w-full h-96 overflow-hidden"></div>
        <div id="post-form-tool-bar" class="w-full pr-3 flex items-center justify-between">
          <label 
            for="upload-file" 
            class="p-2 hover:cursor-pointer hover:bg-green-300/50 rounded-full transition duration-300"
          >
            <img src="/images/camera.svg" alt="upload-icon" class="size-5">
            <input 
              id="upload-file" 
              type="file" 
              name="upload-file" 
              class="hidden" 
              value="">
          </label>
          <button 
            type="submit" 
            class="py-2 px-4 rounded-4xl text-sm bg-gray-500/60 text-white font-semibold hover:bg-slate-800 transition duration-300 focus:bg-slate-800 hover:cursor-pointer"
          >
          ポストする
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function() {
    const uploadsImage = document.getElementById('upload-file');
    const previewContainer = document.getElementById('preview-container');
    const postFormToolBar = document.getElementById('post-form-tool-bar');

    uploadsImage.addEventListener('change', function(event) {
      const files = event.target.files;
      previewContainer.innerHTML = ''; 

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.alt = 'posted-file';
          previewContainer.classList.toggle('hidden');
          postFormToolBar.classList.add('border-t', 'border-slate-100', 'pt-4');
          img.classList.add('size-full', 'rounded-2xl');
          previewContainer.appendChild(img);
        };

        reader.readAsDataURL(file);
      }
    });
  });
</script>
<script>
  const textarea = document.getElementById('content');

  const autoResize = () => {
    textarea.style.height = 'auto'; // 高さをリセット
    textarea.style.height = textarea.scrollHeight + 'px'; // 内容に応じた高さに変更
  };

  textarea.addEventListener('input', autoResize);

  // 初期化（ページ読み込み時にもし内容があれば反映）
  window.addEventListener('DOMContentLoaded', autoResize);
</script>