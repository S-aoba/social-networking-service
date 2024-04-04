const postDeleteModal = document.getElementById('postDeleteModal');
const deleteBtns = document.querySelectorAll('#deleteBtn');

// postDeleteModalを開く
deleteBtns.forEach((deleteBtn) => {
  deleteBtn.onclick = function () {
    postDeleteModal.classList.remove('hidden');
  };
});

// postDeleteModalのキャンセルボタンをクリックしたらmodalを閉じる
const postDeleteCancelBtns = document.querySelectorAll('#postDeleteCancelBtn');

postDeleteCancelBtns.forEach((postDeleteCancelBtn) => {
  postDeleteCancelBtn.addEventListener('click', () => {
    postDeleteModal.classList.add('hidden');
  });
});

// postDeleteModal外をクリックしたらmodalを閉じる
window.onclick = function (e) {
  if (e.target == postDeleteModal) {
    postDeleteModal.classList.add('hidden');
  }
};
