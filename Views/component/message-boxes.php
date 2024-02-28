<?php
$success = \Response\FlashData::getFlashData('success');
$error = \Response\FlashData::getFlashData('error');
?>

<div class="container mt-20 flex justify-center">
  <div class="max-w-md w-full">
    <?php if ($success) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4" role="alert">
        <span><?php echo htmlspecialchars($success); ?></span>
      </div>
    <?php endif; ?>

    <?php if ($error) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">
        <span><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>
  </div>
</div>
