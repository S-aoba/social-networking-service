window.addEventListener('DOMContentLoaded', () => {   
  const deleteButtons = document.querySelectorAll(".delete-conversation-button");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation();

      // このボタンの次の兄弟要素（メニュー）を取得
      const dropdown = button.nextElementSibling;

      if (!dropdown || !dropdown.matches("div[role='menu']")) return;

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

  // 他の場所をクリックしたらすべて閉じる
  document.addEventListener("click", function () {
    document.querySelectorAll("div[role='menu']").forEach((menu) => {
      menu.classList.add("hidden");
    });
  });
});
