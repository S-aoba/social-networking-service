<div class="col-span-8 w-full h-full flex flex-col space-y-4 p-4 bg-white rounded-xl shadow-md">
  <!-- ヘッダー -->
  <div class="flex items-center space-x-2">
    <img src="/images/undo-icon.svg" alt="undo-icon" class="w-5 h-5">
    <div class="text-xl font-semibold"><?= $username ?></div>
  </div>

  <div class="flex flex-col space-y-4">
    <?php foreach($data as $item): ?>
    <div class="flex items-start space-x-4 p-4">
      <img src="/images/default-icon.png" alt="user-icon" class="w-12 h-12 rounded-full">
      <div class="flex flex-col space-y-1">
        <div class="font-medium text-lg"><?= $item->getUsername() ?></div>
        <div class="text-sm text-gray-600"><?= $item->getSelfIntroduction(); ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
