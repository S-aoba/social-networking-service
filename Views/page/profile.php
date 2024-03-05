<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
  <h1 class="text-2xl text-center py-4 bg-gray-200 font-bold">Profile</h1>
  <form id="update-profile-form" action="#" method="post" class="p-6">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <?php if ($profile->getId() !== null) : ?>
      <input type="hidden" name="id" value="<?= $profile->getId() ?>" placeholder="ID"><br>
    <?php endif; ?>
    <div class="flex justify-center mb-4">
      <img id="preview-image" src="<?= htmlspecialchars($profile_image_path ?? 'https://via.placeholder.com/150') ?>" alt="Profile Image" class="w-32 h-32 rounded-full border-4 border-gray-200">
    </div>
    <div class="flex flex-col space-y-5 justify-end mb-4">
      <input id="image" name="image" type="file" accept=".png, .jpg, .jpeg, .gif" required>
      <input class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-800" type="submit" value="Update">
    </div>
    <div class="text-lg mb-4">
      <p><span class="font-semibold">ユーザーネーム:</span> <?= htmlspecialchars($profile->getUsername() ?? 'No username') ?></p>
      <p><span class="font-semibold">年齢:</span> <?= htmlspecialchars($profile->getAge() ?? 'No age') ?></p>
      <p><span class="font-semibold">住所:</span> <?= htmlspecialchars($profile->getAddress() ?? 'No address') ?></p>
      <p><span class="font-semibold">趣味:</span> <?= htmlspecialchars($profile->getHobby() ?? 'No hobby') ?></p>
      <p><span class="font-semibold">自己紹介:</span> <?= htmlspecialchars($profile->getSelfIntroduction() ?? 'No self introduction') ?></p>
    </div>
  </form>
</div>

<script src="/js/profile.js"></script>
