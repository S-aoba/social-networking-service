window.addEventListener('DOMContentLoaded', () => {
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
})