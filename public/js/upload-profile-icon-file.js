document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modal");
  const closeBtn = document.getElementById("close-button");
  const updateProfileIconBtn = document.getElementById(
    "update-profile-icon-button"
  );

  closeBtn.addEventListener("click", function () {
    modal.classList.add("hidden");
  });

  const imagePath = document.getElementById("upload-file");
  imagePath.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const img = document.getElementById("preview-image");
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);

      if (updateProfileIconBtn) {
        const updateBtn = document.createElement("button");
        updateBtn.type = "submit";
        updateBtn.classList.add(
          "ml-3",
          "py-2",
          "px-4",
          "text-sm",
          "bg-slate-500",
          "text-white",
          "rounded-2xl",
          "cursor-pointer",
          "transition",
          "duration-300",
          "hover:brightness-110"
        );
        updateBtn.innerText = "アイコンを保存する";
        updateBtn.setAttribute("form", "update-profile-icon-form");

        updateProfileIconBtn.appendChild(updateBtn);
      }
    }
  });
});
