<div class="w-full h-fit">
    <div class="w-fit p-3 flex space-x-2 justify-start items-center rounded-3xl hover:cursor-pointer hover:bg-stone-300/45 transition duration-300">
        <img src="<?php echo htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8'); ?>" 
             alt="<?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>-icon" 
             class="w-6 h-6 text-black"
        >
        <p class="font-semibold"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>