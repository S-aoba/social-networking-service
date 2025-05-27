document.addEventListener("DOMContentLoaded", function () {
  const updateProfileForm = document.getElementById("update-profile-form");
  const errorMessage = document.getElementById("update-profile-error-message");

  updateProfileForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(updateProfileForm);
    errorMessage.textContent = "";
    errorMessage.classList.add("hidden");

    const res = await fetch("api/profile", {
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
