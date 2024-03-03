<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
  <h1 class="text-2xl text-center py-4 bg-gray-200 font-bold">Profile</h1>
  <div class="p-6">
    <div class="flex justify-center mb-4">
      <img src="<?= htmlspecialchars($user->getProfileImage() ?? 'https://via.placeholder.com/150') ?>" alt="Profile Image" class="w-32 h-32 rounded-full">
    </div>
    <p class="text-lg mb-4">
      <span class="font-semibold">Username:</span>
      <?= htmlspecialchars($user->getUsername() ?? 'No username') ?>
    </p>
    <p class="text-lg mb-4">
      <span class="font-semibold">Age:</span>
      <?= htmlspecialchars($user->getAge() ?? 'No age') ?>
    </p>
    <p class="text-lg mb-4">
      <span class="font-semibold">Address:</span>
      <?= htmlspecialchars($user->getAddress() ?? 'No address') ?>
    </p>
    <p class="text-lg mb-4">
      <span class="font-semibold">Hobby:</span>
      <?= htmlspecialchars($user->getHobby() ?? 'No hobby') ?>
    </p>
    <p class="text-lg mb-4">
      <span class="font-semibold">Self Introduction:</span>
      <?= htmlspecialchars($user->getSelfIntroduction() ?? 'No self introduction') ?>
    </p>
  </div>
</div>
