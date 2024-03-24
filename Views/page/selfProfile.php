<div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
  <div class="px-4 py-6">
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($profile->getUsername()) ?></h1>
    </div>
    <img src="<?= htmlspecialchars($profile->getProfileImage()) ?>" alt="profile image" class="mt-4 rounded-lg">
    <div class="mt-4">
      <p class="text-sm text-gray-600">Age: <?= htmlspecialchars($profile->getAge()) ?></p>
      <p class="text-sm text-gray-600">Address: <?= htmlspecialchars($profile->getAddress()) ?></p>
      <p class="text-sm text-gray-600">Hobby: <?= htmlspecialchars($profile->getHobby()) ?></p>
      <p class="text-sm text-gray-600">Self Introduction: <?= htmlspecialchars($profile->getSelfIntroduction()) ?></p>
    </div>
    <?php if ($user->getId() === $profile->getUserId()) : ?>
      <a href="/edit/profile" class="block w-full mt-6 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Edit</a>
    <?php endif; ?>
  </div>
</div>
<script src="/js/follow.js"></script>
