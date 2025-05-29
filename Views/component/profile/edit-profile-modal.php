<?php
$profileInfoList = [
  'name' => [
    'label' => '名前',
    'name' => 'username',
    'type' => 'text',
    'value' => $queryUser->getUsername()
  ],
  'age' => [
    'label' => '年齢',
    'name' => 'age',
    'type' => 'number',
    'value' => $queryUser->getAge()
  ],
  'address' => [
    'label' => '住所',
    'name' => 'address',
    'type' => 'text',
    'value' => $queryUser->getAddress()
  ],
  'hobby' => [
    'label' => '趣味',
    'name' => 'hobby',
    'type' => 'text',
    'value' => $queryUser->getHobby()
  ],
  'selfIntroduction' => [
    'label' => '自己紹介',
    'name' => 'self_introduction',
    'rows' => 4,
    'value' => $queryUser->getSelfIntroduction()
  ],
];
?>



<div 
  id="modal" 
  role="dialog" 
  class="relative z-50 hidden" 
  aria-labelledby="modal-title" 
  aria-modal="true"
>
  <div 
    class="fixed inset-0 bg-gray-500/75 transition-opacity" 
    aria-hidden="true"
  ></div>
  
  <div class="fixed inset-0 flex items-center justify-center">

    <!-- Edit Profile Card -->
    <div class="w-[30rem] h-auto p-3 rounded-2xl bg-white">
      <!-- Header -->
      <div class="pb-3 pr-3 flex items-center">
        <button
          id="close-button"
          type="button"
          aria-label="Close" 
          class="mr-10 size-8 flex items-center justify-center rounded-full cursor-pointer  transition duration-300 hover:bg-gray-400/50">
          <img src="images/close-icon.svg" alt="" class="size-6">
        </button>
        <span class="text-lg font-semibold">プロフィールを編集</span>
      </div>

      <!-- Profile Icon -->
      <div class="py-5 flex flex-col items-center justify-center space-y-3">

        <div id="update-profile-icon-error-message" class="hidden w-full my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>

        <!-- Preview Icon -->
        <div class="relative size-36 rounded-full"> 
          <img
            id="preview-image"
            src="<?= $queryUser->getImagePath(); ?>"
            alt="user-icon" 
            class="size-full object-fill rounded-full"
          >
          <div class="absolute inset-0 size-36 rounded-full bg-[rgba(0,0,0,0.3)]"></div>

          <!-- Camera Icon -->
          <form id="update-profile-icon-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
            <label for="upload-file">
              <div class="absolute inset-13 flex items-center justify-center size-10 bg-black/70 rounded-full transition duration-300 brightness-95 cursor-pointer hover:brightness-110">
                <img 
                  src="/images/camera.svg" 
                  alt="camera-icon" class="size-5"
                >
                <input 
                  id="upload-file"
                  type="file"
                  name="upload-file"
                  class="hidden"
                  accept="image/png, image/jpg, image/jpeg, image/gif, image/webp"
                >
              </div>
            </label>
          </form>
        </div>

        <!-- Update profile icon button -->
        <div id="update-profile-icon-button"></div>
      </div>

      <!-- Profile Information -->
      <form id="update-profile-form" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
        <input type="hidden" name="user_id" value="<?= $queryUser->getUserId(); ?>">
        
        <div class="pt-3 flex flex-col space-y-4 border-t border-slate-200">
          <div id="update-profile-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>
          <!-- data -->
          <?php foreach ($profileInfoList as $key => $data): ?>
            <?php if ($key === 'selfIntroduction') : ?>
              <label for="<?= $data['name']; ?>" class="text-slate-500">
                <?= $data['label']; ?>
                <textarea 
                  name="<?= $data['name']; ?>" 
                  class="p-2 w-full rounded-md border border-gray-300 shadow-sm text-black"
                  rows="<?= $data['rows']; ?>"
                ><?= $data['value']; ?></textarea>
              </label>
            <?php else: ?>
              <label for="<?= $data['name']; ?>" class="text-slate-500">
                <?= $data['label']; ?>
                <input 
                  type="<?= $data['type']; ?>" 
                  name="<?= $data['name']; ?>" 
                  class="p-2 w-full rounded-md border border-gray-300 shadow-sm text-black"
                  value="<?= $data['value']; ?>"
                >
              </label>
            <?php endif; ?>
          <?php endforeach; ?>
          <div class="flex items-center justify-end">
            <button
              type="submit"
              class="px-4 py-1.5 bg-black text-white text-sm font-semibold rounded-3xl cursor-pointer transition duration-300 hover:opacity-75"
            >
            保存
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/js/update-profile-form.js"></script>
<script src="/js/update-profile-icon-form.js"></script>
<script src="/js/upload-profile-icon-file.js"></script>