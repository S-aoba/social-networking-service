const postDeleteModals = document.querySelectorAll('#postDeleteModal');
const deleteBtns = document.querySelectorAll('#deleteBtn');

// postDeleteModalを開く
deleteBtns.forEach((deleteBtn) => {
  deleteBtn.onclick = function () {
    const postId = deleteBtn.dataset.postId;

    postDeleteModals.forEach((postDeleteModal) => {
      if (postDeleteModal.dataset.postId === postId) {
        postDeleteModal.classList.remove('hidden');

        window.onclick = function (e) {
          if (e.target === postDeleteModal) {
            postDeleteModal.classList.add('hidden');
          }
        };
      }
    });
  };
});

// postDeleteModalのキャンセルボタンをクリックしたらmodalを閉じる
const postDeleteCancelBtns = document.querySelectorAll('#postDeleteCancelBtn');

postDeleteCancelBtns.forEach((postDeleteCancelBtn) => {
  postDeleteCancelBtn.addEventListener('click', () => {
    postDeleteModals.forEach((postDeleteModal) => {
      postDeleteModal.classList.add('hidden');
    });
  });
});
