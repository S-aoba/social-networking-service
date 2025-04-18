<div class="col-span-8 w-full h-full flex flex-col overflow-auto">
  <?php include "Views/component/home/tab.php" ?>
  <?php include "Views/component/post-form.php" ?>

  <?php foreach ($followerPosts as $data): ?>
    <?php include "Views/component/article.php" ?>
  <?php endforeach; ?>
   
</div>

<script src="/js/like.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const dropdownWrappers = document.querySelectorAll(".menu-button");

  dropdownWrappers.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation(); // 他のクリックイベントとのバッティング防止

      const dropdown = button.closest("div.relative").querySelector("div[role='menu']");

      // すべてのドロップダウンを閉じる（他の開いてるメニューを閉じるため）
      document.querySelectorAll("div[role='menu']").forEach((menu) => {
        if (menu !== dropdown) {
          menu.classList.add("hidden");
        }
      });

      // 対象のメニューの表示・非表示を切り替える
      dropdown.classList.toggle("hidden");
    });
  });

  // ドキュメント外クリック時に全ドロップダウンを閉じる
  document.addEventListener("click", function () {
    document.querySelectorAll("div[role='menu']").forEach((menu) => {
      menu.classList.add("hidden");
    });
  });
});
</script>
<script>
  // 削除ボタンに対してモーダルを開く処理
  const deleteButtons = document.querySelectorAll(".delete-post-button");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation();

      const modal = button.closest("div.relative").querySelector(".delete-post-modal");
      if (modal) {
        modal.classList.remove("hidden");
      }

      const cancelBtn = modal.querySelector(".cancel-delete-button");
        if (cancelBtn) {
          cancelBtn.addEventListener("click", function () {
            modal.classList.add("hidden");
          });
        }

      // メニューは閉じておく
      const menu = button.closest("div[role='menu']");
      if (menu) {
        menu.classList.add("hidden");
      }
    });
  });

</script>