window.addEventListener("DOMContentLoaded", () => {
  const textarea = document.getElementById("content");

  const defaultHeight = "40";

  const autoResize = () => {
    textarea.style.height = "40px";
    const scrollHeight = textarea.scrollHeight;
    textarea.style.height = Math.max(scrollHeight, defaultHeight) + "px";
  };

  textarea.addEventListener("input", autoResize);
});
