document.addEventListener("DOMContentLoaded", function () {
  const createPostForm = document.getElementById("create-post-form");
  const errorMessage = document.getElementById("create-post-error-message");

  const submitBtn = document.getElementById("submit-btn");

  const loadingImg = document.createElement("img");
  loadingImg.src = "/images/loading-icon.svg";
  loadingImg.alt = "loading";
  loadingImg.classList.add("animate-spin");

  createPostForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(createPostForm);
    errorMessage.classList.add("hidden");
    errorMessage.textContent = "";

    showLoading();

    const res = await fetch("api/post", {
      method: "POST",
      body: formData,
    });

    const data = await res.json();
    if (data.status === "success") window.location.reload();
    else {
      showError(data.message);
      resetBtn();
    }
  });

  function showError(message) {
    if (typeof message === "string") {
      errorMessage.textContent = message;
    } else if (typeof message === "object") {
      const ul = document.createElement("ul");
      Object.keys(message).forEach((key) => {
        const li = document.createElement("li");
        li.classList.add("list-none");
        li.innerText = message[key];
        ul.appendChild(li);
      });
      errorMessage.appendChild(ul);
    } else {
      errorMessage.textContent =
        "Something went wrong on our end. Please try again later.";
    }
    errorMessage.classList.remove("hidden");
  }

  function showLoading() {
    submitBtn.textContent = "";
    submitBtn.append(loadingImg);
    submitBtn.classList.add("flex", "items-center", "justify-center");
  }

  function resetBtn() {
    submitBtn.innerHTML = '';
    submitBtn.textContent = 'ポストする'
    submitBtn.classList.remove("flex", "items-center", "justify-center");
  }
});
