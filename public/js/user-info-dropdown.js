window.document.addEventListener("DOMContentLoaded", function () {
  const button = document.getElementById("user-info-menu-button");
  const menu = document.getElementById("user-info-menu");

  button.addEventListener("click", () => {
    menu.classList.toggle("hidden");
  });

  document.addEventListener("click", (event) => {
    if (!button.contains(event.target) && !menu.contains(event.target)) {
      menu.classList.add("hidden");
    }
  });
});
