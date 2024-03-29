 <!-- TODO:アイコンをホバーした時にbgをアイコン側だけにして、数字には左端に少しかかるくらいにする -->
 <?php if ($data['isLike']) : ?>
   <form action="#" method="POST" id="unlikeForm">
     <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
     <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
     <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
     <button type="submit">
       <div class="h-8 w-8 flex items-center justify-center space-x-1 cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
         <img class="h-4 w-4" src="/images/post-liked-icon.svg" alt="いいね">
         <span class="text-[#f0609f]"><?= $data['postLikeCount'][0]["COUNT(*)"] ?></span>
       </div>
     </button>
   </form>
 <?php else : ?>
   <form action="#" method="POST" id="likeForm">
     <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
     <input type="hidden" name="post_id" value="<?= $data['post']->getId() ?>">
     <input type="hidden" name="post_user_id" value="<?= $data['post']->getUserId() ?>">
     <input type="hidden" name="post_content" value="<?= $data['post']->getContent() ?>">
     <button type="submit">
       <div class="h-8 w-8 flex items-center justify-center space-x-1 cursor-pointer hover:bg-slate-200 transition-colors duration-300 rounded-full">
         <img class="h-4 w-4" src="/images/post-like-icon.svg" alt="いいね">
         <span><?= $data['postLikeCount'][0]["COUNT(*)"] ?></span>
       </div>
     </button>
   </form>
 <?php endif; ?>
