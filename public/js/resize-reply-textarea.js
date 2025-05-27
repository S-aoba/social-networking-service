document.addEventListener("DOMContentLoaded", function() {
  const textarea = document.getElementById("content");
  textarea.addEventListener('input', () => {
    textarea.style.height = "auto";
    textarea.style.height = textarea.scrollHeight + "px";
  })
})

