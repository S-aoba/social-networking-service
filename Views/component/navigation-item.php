<a href="<?= htmlspecialchars($item['linkPath'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-fit">
    <div class="relative w-fit p-3 flex space-x-2 justify-start items-center rounded-3xl hover:cursor-pointer hover:bg-slate-100 transition duration-300">

        <?php if($item['label'] === 'notification'): ?>
            <?php if($hasNotification !== null): ?>
                <?php include "Views/component/indicator.php" ?>
            <?php endif; ?>
        <?php endif; ?>
            
        <img src="<?= htmlspecialchars($item['imagePath'], ENT_QUOTES, 'UTF-8'); ?>" 
             alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>-icon" 
             class="w-6 h-6 text-black"
        >
        <p class="font-semibold"><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</a>