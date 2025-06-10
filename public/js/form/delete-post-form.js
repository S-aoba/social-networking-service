document.addEventListener("DOMContentLoaded", function () {
  const deletePostForms = document.querySelectorAll(".delete-post-form");

  deletePostForms.forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);

      const errorMessage = form.querySelector(".delete-post-error-message");
      errorMessage.textContent = "";
      errorMessage.classList.add("hidden");

      const res = await fetch("api/delete/post", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.status === "success") {
        if (window.location.pathname.startsWith("/post")) {
          window.location.href = "/";
        } else {
          window.location.reload();
        }
      } else {
        showError(data.message, errorMessage)
      }
    });
  });

  function showError(message, errorMessage) {
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
