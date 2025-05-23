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
        const button = form.querySelector('button[type="submit"]');
        if (button) {
          const likeIconImg = button.querySelector("img");
          const likeCountDiv = button.querySelector("div.like-count");

          const liked = data.liked;
          const likeCount = data.likeCount;

          // TODO: いいねのbuttonにaria-label属性をつけて、imgのaltは空文字にする
          // いいねをしていない場合
          if (liked === false) {
            console.log("いいねをしました。");
            likeIconImg.onload = function () {
              if (
                likeCountDiv &&
                typeof likeCount === "number" &&
                likeCount > 0
              ) {
                likeCountDiv.textContent = likeCount;
                likeCountDiv.classList.remove("hidden", "text-slate-400");
                likeCountDiv.classList.add("text-rose-500");
              }
            };
            likeIconImg.src = "/images/like-icon.svg";
            likeIconImg.alt = "Like";
          }
          // いいねをしている場合
          else {
            console.log("いいねを取り消しました。");
            likeIconImg.src = "/images/unlike-icon.svg";
            likeIconImg.alt = "Unlike";
          }
        }
      } else if (errorMessage && data.status === "error") {
        errorMessage.textContent = data.message;
        errorMessage.classList.remove("hidden");
      } else {
        console.log("error message field is nothing.");
      }
    });
  });
});
