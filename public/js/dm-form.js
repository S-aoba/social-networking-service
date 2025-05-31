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
      const messages = data.message;

      errorMessage.innerHTML = "<ul>" + Object.values(messages).map(msg => `<li>${msg}</li>`).join("") + "</ul>";
      errorMessage.classList.remove("hidden");
    }
  });
});
