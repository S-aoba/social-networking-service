window.addEventListener("DOMContentLoaded", function () {
  const replyForm = document.getElementById("reply-form");
  const errorMessage = document.getElementById("reply-error-message");

  replyForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(replyForm);
    errorMessage.textContent = "";
    errorMessage.classList.add("hidden");

    const res = await fetch("api/reply", {
      method: "POST",
      body: formData,
    });

    const data = await res.json();

    if (data.status === "success") {
      window.location.reload();
    } else {
      
      const messages = data.message;

      errorMessage.innerHTML = "<ul>" + Object.values(messages).map(msg => `<li>${msg}</li>`).join("") + "</ul>";
      errorMessage.classList.remove("hidden");
    }
  });
});
