window.addEventListener('DOMContentLoaded', () => {
  // 削除メニュー内の「削除」ボタンを取得
  const deleteButtons = document.querySelectorAll(".delete-conversation-action-button");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation();

      const targetId = button.dataset.target;
      const modal = document.getElementById(targetId);

      if (!modal) return;

      // モーダル表示
      modal.classList.remove("hidden");

      // キャンセルボタンを押すとモーダルを閉じる
      const cancelBtn = modal.querySelector(".cancel-delete-button");
      if (cancelBtn) {
        cancelBtn.addEventListener("click", function () {
          modal.classList.add("hidden");
        }, { once: true });
      }

      // メニューは閉じておく 
      const menu = button.closest("div[role='menu']");
      if (menu) {
        menu.classList.add("hidden");
      }
    });
  });
});
