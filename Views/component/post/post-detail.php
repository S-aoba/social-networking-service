<?php
    $imagePath = $imagePath === null ? 'https://picsum.photos/200/300' : $imagePath;
?>

<div class="col-span-8 w-full h-full flex flex-col">
  <div class="flex items-center gap-x-2 p-2">
    <img src="/images/undo-icon.svg" alt="undo-icon" class="size-5">
    <span class="font-semibold">ポスト</span>
  </div>
  <div class="w-full h-fit border-b border-slate-200">
    <?php include "Views/component/article.php" ?>
  </div>
  <div class="flex">
    <div class="p-5">
      <div class="size-10 rounded-full overflow-hidden">
        <img src="<?php echo $imagePath ?>" alt="user-icon" class="w-full h-full object-cover">
      </div>
    </div>
    <form action="form/reply" method="post" class="w-full flex flex-col space-y-4">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
      <input type="hidden" name="parent_post_id" value="<?= $data['post']->getId(); ?>">

      <textarea name="content" id="content" class="w-full resize-none p-2 focus:outline-none" placeholder="返信をポスト"></textarea>
      <div class="w-full flex items-center justify-end my-2 pr-2">
        <button 
          type="submit" 
          class="py-2 px-4 rounded-4xl text-sm bg-gray-500/60 text-white font-semibold hover:bg-slate-800 transition duration-300 focus:bg-slate-800"
        >
        返信
      </button>
      </div>
    </form>
  </div>
</div>