document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("create-conversation-modal");
  const closeButton = document.getElementById("close-button");

  closeButton.addEventListener("click", function () {
    modal.classList.add("hidden");
  });
});
  