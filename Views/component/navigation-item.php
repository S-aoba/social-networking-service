<a href="<?php echo htmlspecialchars($item['linkPath'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-fit">
    <div class="relative w-fit p-3 flex space-x-2 justify-start items-center rounded-3xl hover:cursor-pointer hover:bg-slate-100 transition duration-300">

        <?php if($item['label'] === 'notification'): ?>
            <?php if($hasNotification): ?>
                <div 
                    id="indicator" 
                    class="absolute top-2 left-6 w-3 h-3 bg-blue-500 rounded-full shadow-lg ring-2 ring-white animate-pulse z-10 border-2 border-blue-300"
                    style="box-shadow: 0 0 8px 2px rgba(59,130,246,0.5);"
                    title="新着通知あり"
                ></div>
            <?php endif; ?>
        <?php endif; ?>
            
        <img src="<?php echo htmlspecialchars($item['imagePath'], ENT_QUOTES, 'UTF-8'); ?>" 
             alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>-icon" 
             class="w-6 h-6 text-black"
        >
        <p class="font-semibold"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</a>