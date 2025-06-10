document.addEventListener("DOMContentLoaded", function () {
  const uploadsImage = document.getElementById("upload-file");
  const previewContainer = document.getElementById("preview-container");
  const postFormToolBar = document.getElementById("post-form-tool-bar");

  uploadsImage.addEventListener("change", function (event) {
    const files = event.target.files;
    previewContainer.innerHTML = "";

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();

      reader.onload = function (e) {
        const img = document.createElement("img");
        img.src = e.target.result;
        img.alt = "posted-file";
        previewContainer.classList.remove("hidden");
        postFormToolBar.classList.add("border-t", "border-slate-100", "pt-4");
        img.classList.add("size-full", "rounded-2xl");
        previewContainer.appendChild(img);
      };

      reader.readAsDataURL(file);
    }
  });
});
