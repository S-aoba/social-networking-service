document.addEventListener("DOMContentLoaded", function () {
  const updateProfileIconForm = document.getElementById(
    "update-profile-icon-form"
  );
  const errorMessage = document.getElementById(
    "update-profile-icon-error-message"
  );

  updateProfileIconForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(updateProfileIconForm);
    errorMessage.textContent = "";
    errorMessage.classList.add("hidden");

    const res = await fetch("api/profile/icon", {
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
