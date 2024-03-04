<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
  <h1 class="text-2xl text-center py-4 bg-gray-200 font-bold">Profile</h1>
  <div class="p-6">
    <div class="flex justify-center mb-4">
      <img src="<?= htmlspecialchars($user->getProfileImage() ?? 'https://via.placeholder.com/150') ?>" alt="Profile Image" class="w-32 h-32 rounded-full border-4 border-gray-200">
    </div>
    <div class="text-lg mb-4">
      <p><span class="font-semibold">ユーザーネーム:</span> <?= htmlspecialchars($user->getUsername() ?? 'No username') ?></p>
      <p><span class="font-semibold">年齢:</span> <?= htmlspecialchars($user->getAge() ?? 'No age') ?></p>
      <p><span class="font-semibold">住所:</span> <?= htmlspecialchars($user->getAddress() ?? 'No address') ?></p>
      <p><span class="font-semibold">趣味:</span> <?= htmlspecialchars($user->getHobby() ?? 'No hobby') ?></p>
      <p><span class="font-semibold">自己紹介:</span> <?= htmlspecialchars($user->getSelfIntroduction() ?? 'No self introduction') ?></p>
    </div>
  </div>
</div>
