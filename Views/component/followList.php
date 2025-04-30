<div class="col-span-8 w-full h-full flex flex-col space-y-4 p-4 bg-white rounded-xl shadow-md">
  <div class="flex items-center space-x-2">
     <?php include "Views/component/undo.php" ?>
    <div class="text-xl font-semibold"><?= $authUser->getUsername(); ?></div>
  </div>

  <div class="flex flex-col divide-y divide-slate-200">
    <?php foreach($data as $item): ?>
    <div class="relative flex items-start space-x-4 p-4">
      <a href="/profile?user=<?= $item->getUsername(); ?>" class="absolute inset-0 hover:bg-slate-400 opacity-20 transition duration-300"></a>
      <img src="<?= $item->getImagePath(); ?>" alt="user-icon" class="w-12 h-12 rounded-full">
      <div class="flex flex-col space-y-1">
        <div class="font-medium text-lg"><?= $item->getUsername() ?></div>
        <div class="text-sm text-gray-600"><?= $item->getSelfIntroduction(); ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
