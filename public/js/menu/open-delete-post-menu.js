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