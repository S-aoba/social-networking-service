<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
  <h1 class="text-2xl text-center py-4 bg-gray-200 font-bold">Profile</h1>
  <form id="update-profile-image-form" action="#" method="post" class="p-6">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <?php if ($profile->getId() !== null) : ?>
      <input type="hidden" name="id" value="<?= $profile->getId() ?>" placeholder="ID"><br>
    <?php endif; ?>
    <div class="flex justify-center mb-4">
      <img id="preview-image" src="<?= htmlspecialchars($profile_image_path ?? 'https://via.placeholder.com/150') ?>" alt="Profile Image" class="w-32 h-32 rounded-full border-4 border-gray-200">
    </div>
    <div class="flex flex-col space-y-5 justify-end mb-4">
      <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif" required>
      <input class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-800 hover:cursor-pointer" type="submit" value="UpdateProfileImage">
    </div>
  </form>

  <form id="update-profile-form" action="#" method="post" class="p-6 text-base mb-4 flex flex-col space-y-5">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <div>
      <p class="font-bol mb-2">ユーザー名</p>
      <input class="p-3 border border-gray-300 rounded-lg w-full" value="<?= htmlspecialchars($profile->getUsername() ?? 'No Username') ?>" name="username" />
    </div>
    <div>
      <p class="font-bol mb-2">年齢</p>
      <input class="p-3 border border-gray-300 rounded-lg w-full" value="<?= htmlspecialchars($profile->getAge() ?? '0') ?>" name="age" />
    </div>
    <div>
      <p class="font-bol mb-2">住所</p>
      <input class="p-3 border border-gray-300 rounded-lg w-full" value="<?= htmlspecialchars($profile->getAddress() ?? 'No Address') ?>" name="address" />
    </div>
    <div>
      <p class="font-bol mb-2">趣味</p>
      <input class="p-3 border border-gray-300 rounded-lg w-full" value="<?= htmlspecialchars($profile->getHobby() ?? 'No Hobby') ?>" name="hobby" />
    </div>
    <div>
      <p class="font-bol mb-2">自己紹介</p>
      <textarea class="p-3 border border-gray-300 rounded-lg w-full" name="self_introduction"><?= htmlspecialchars($profile->getSelfIntroduction() ?? 'No Self Introduction') ?></textarea>
    </div>
    <div class="flex justify-end">
      <input class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-800 hover:cursor-pointer" type="submit" value="UpdateProfile">
    </div>
  </form>
</div>

<script src="/js/profile.js"></script>
