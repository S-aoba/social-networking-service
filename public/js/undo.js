document.addEventListener('DOMContentLoaded', () => {
  const undoButton = document.getElementById('undoButton');

  if (undoButton) {
    undoButton.addEventListener('click', () => {
      window.history.back();
    });
  }
}); 