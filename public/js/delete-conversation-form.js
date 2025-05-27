document.addEventListener("DOMContentLoaded", function () {
  const deleteConversationForms = document.querySelectorAll(
    ".delete-conversation-form"
  );

  deleteConversationForms.forEach((form) => {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);

      const errorMessage = document.querySelector(
        ".delete-conversation-error-message"
      );
      errorMessage.textContent = "";
      errorMessage.classList.add("hidden");

      const res = await fetch("api/delete/conversation", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.status === "success") {
        window.location.href = data.redirect;
      } else {
        errorMessage.textContent = data.message;
        errorMessage.classList.remove("hidden");
      }
    });
  });
});
