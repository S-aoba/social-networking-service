document.addEventListener("DOMContentLoaded", function () {
  const createPostForm = document.getElementById("create-post-form");
  const errorMessage = document.getElementById("create-post-error-message");

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
      showError(data.message);
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
});
