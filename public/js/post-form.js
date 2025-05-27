document.addEventListener("DOMContentLoaded", function () {
  const createPostForm = document.getElementById("create-post-form");
  const errorMessage = document.getElementById("create-post-error-message");
  const uploadsImage = document.getElementById("upload-file");
  const previewContainer = document.getElementById("preview-container");
  const postFormToolBar = document.getElementById("post-form-tool-bar");
  const textarea = document.getElementById("content");

  if (createPostForm) {
    createPostForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(createPostForm);
      errorMessage.classList.add("hidden");
      errorMessage.textContent = "";

      const res = await fetch("api/post", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();
      if (data.status === "success") window.location.reload();
      else {
        errorMessage.textContent = data.message;
        errorMessage.classList.remove("hidden");
      }
    });
  }

  if (uploadsImage) {
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
  }

  // textareaの自動リサイズ
  if (textarea) {
    const autoResize = () => {
      textarea.style.height = "auto";
      textarea.style.height = textarea.scrollHeight + "px";
    };

    textarea.addEventListener("input", autoResize);
    window.addEventListener("DOMContentLoaded", autoResize);
  }
});
