<?php if (is_null($profile->getUploadFullPathOfHeader())) : ?>
  <div class="h-48 w-full">
    <div class="absolute inset-0 w-full h-full bg-black opacity-20"></div>
    <img id="blank-preview-header-image" src="" alt="" class="hidden object-cover h-48 w-full">
    <div class="absolute inset-0 w-full h-full z-50 flex items-center justify-center">
      <label for="header-image" class="p-3 border rounded-full bg-black opacity-75 h-14 w-14 cursor-pointer">
        <img class="bg-cover" src="/images/header-image-change.svg" alt="ヘッダーの変更アイコン">
      </label>
      <input id="header-image" name="header-image" type="file" class="hidden" accept=".png, .jpg, .jpeg, .gif, .mp4">
    </div>
  </div>
<?php else : ?>
  <div class="absolute inset-0 w-full h-full bg-black opacity-20"></div>
  <img id="preview-header-image" class="object-cover h-48 w-full" src="<?= $profile->getUploadFullPathOfHeader() ?>" alt="バナー画像">
  <div class="absolute inset-0 w-full h-full z-50 flex items-center justify-center">
    <label for="header-image" class="p-3 border rounded-full bg-black opacity-75 h-14 w-14 cursor-pointer">
      <img class="bg-cover" src="/images/header-image-change.svg" alt="ヘッダーの変更アイコン">
    </label>
    <input id="header-image" name="header-image" type="file" class="hidden" accept=".png, .jpg, .jpeg, .gif, .mp4">
  </div>
<?php endif; ?>
