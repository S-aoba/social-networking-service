<div class="size-40 absolute -bottom-20 left-5 border-8 border-white rounded-full bg-black opacity-20 z-40"></div>
<img id="preview-profile-image" src="<?= htmlspecialchars($profile->getProfileImage()) ?>" alt="プロフィール画像" class="size-40 absolute -bottom-20 left-5 border-8 border-white rounded-full bg-white">
<div class="z-50 size-40 absolute -bottom-20 left-5 flex items-center justify-center">
  <label for="profile-image" class="p-3 border rounded-full bg-black opacity-75 h-14 w-14 cursor-pointer">
    <img class="bg-cover" src="/images/header-image-change.svg" alt="ユーザーアイコンの変更アイコン" accept=".png, .jpg, .jpeg, .gif, .mp4">
  </label>
  <input id="profile-image" name="profile-image" type="file" class="hidden">
</div>
