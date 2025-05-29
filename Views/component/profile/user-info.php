<?php if ($val !== null): ?>
  <div>
    <?php if ($key === 'username'): ?>
      <span class="text-lg font-semibold">
        <?= $val ?>
      </span>
    <?php elseif ($key === 'address'): ?>
      <div class="w-full flex space-x-2 items-center justify-start">
        <img src="/images/address.svg" alt="address-icon" class="size-5">
        <span class="text-sm text-slate-500">
          <?= $val ?>
        </span>
      </div>
    <?php else :?>
      <span class="text-sm">
        <?= $val ?>
      </span>
    <?php endif; ?>
  </div>
<?php endif; ?>