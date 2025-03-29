<div class="max-w-[550px] w-full border-r border-slate-200">
  <!-- TabList -->
   <div class="w-full py-3 text-slate-400 text-sm border-b border-slate-200 flex">
    <div class="w-1/2 text-center">
      <p>おすすめ</p>
    </div>
    <div class="w-1/2 text-center">
      <p>
        フォロー中
      </p>
    </div>
   </div>
  <!-- Post Form -->
   <?php include "Views/component/post-form.php"  ?>
  <!-- Article -->
   <?php foreach ($followerPosts as $data): ?>
    <?php include "Views/component/article.php"  ?>
   <?php endforeach ;?>

</div>