document.addEventListener("DOMContentLoaded", function () {
  const directMessageForm = document.getElementById("direct-message-form");
  const errorMessage = document.getElementById("dm-error-message");

  directMessageForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(directMessageForm);
    errorMessage.textContent = "";
    errorMessage.classList.add("hidden");

    const res = await fetch("api/direct-message", {
      method: "POST",
      body: formData,
    });

    const data = await res.json();

    if (data.status === "success") {
      window.location.href = data.redirect;
    } else {
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
