document.addEventListener("DOMContentLoaded", function () {
  const notificationForms = document.querySelectorAll(".notification-form");

  notificationForms.forEach((form) => {
    form.addEventListener("click", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      const errorMessage = form.querySelector(".notification-error-message");
      errorMessage.textContent = "";
      errorMessage.classList.add("hidden");

      const res = await fetch("/api/notification/read", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.status === "success") {
        window.location.href = data.redirect;
      } else {
        showError(data.message, errorMessage);
      }
    });
  });

  function showError(message, errorMessage) {
    errorMessage.innerHTML = "";
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
