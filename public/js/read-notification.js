document.addEventListener("DOMContentLoaded", function () {
  const notificationForms = document.querySelectorAll(".notification-form");

  notificationForms.forEach((form) => {
    form.addEventListener("click", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      const errorMessage = document.querySelector('.notification-error-message');
      errorMessage.textContent = '';
      errorMessage.classList.add('hidden');

      const res = await fetch("/api/notification/read", {
        method: "POST",
        body: formData,
      });

      const data = await res.json();

      if (data.status === "success") {
        window.location.href = data.redirect;
      } else {
        const message = data.message;

        errorMessage.textContent = message;
        errorMessage.classList.remove('hidden');
      }
    });
  });
});
