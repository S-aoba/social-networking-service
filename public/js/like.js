window.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".post-form").forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      const errorMessage = form.querySelector(".like-error-message");
      if (errorMessage) {
        errorMessage.classList.add("hidden");
        errorMessage.textContent = "";
      }

      const res = await fetch("api/like", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.status === "success") {
        // アイコンとカウントの更新
        const button = form.querySelector('button[type="submit"]');
        if (button) {
          // アイコン部分を探す
          const iconImg = button.querySelector("img");
          const countDiv = button.querySelector("like-count");
          if (iconImg) {
            if (data.liked) {
              iconImg.src = "/images/unlike-icon.svg";
              iconImg.alt = "unlike-icon";
            } else {
              iconImg.src = "/images/like-icon.svg";
              iconImg.alt = "unlike-icon";
            }
          }
          if (countDiv) {
            countDiv.textContent = data.likeCount;
            if (data.liked) {
              countDiv.classList.remove("text-slate-400");
              countDiv.classList.add("text-rose-500");
            } else {
              countDiv.classList.remove("text-rose-500");
              countDiv.classList.add("text-slate-400");
            }
          }
        }
      } else if (errorMessage) {
        errorMessage.textContent = data.message;
        errorMessage.classList.remove("hidden");
      }
    });
  });
});
