document.addEventListener("DOMContentLoaded", () => {
  const conversationForms = document.querySelectorAll(
    ".create-conversation-form"
  );
  const errorMessage = document.getElementById("conversation-error-message");

  conversationForms.forEach((form) => {
    form.addEventListener("click", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      errorMessage.textContent = "";
      errorMessage.classList.add("hidden");

      const res = await fetch("api/conversation", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.status === "success") {
        // Modalを閉じる処理
        window.location.href = data.redirect;
      } else {
        showError(data.message);
      }
    });
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
