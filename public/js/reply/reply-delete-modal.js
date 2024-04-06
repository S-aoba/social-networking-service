const replyDeleteModals = document.querySelectorAll('#replyDeleteModal');
const replyDeleteBtns = document.querySelectorAll('#replyDeleteBtn');

// replyDeleteModalを開く
replyDeleteBtns.forEach((deleteBtn) => {
  deleteBtn.onclick = function () {
    const replyId = deleteBtn.dataset.replyId;

    replyDeleteModals.forEach((replyDeleteModal) => {
      if (replyDeleteModal.dataset.replyId === replyId) {
        replyDeleteModal.classList.remove('hidden');

        window.onclick = function (e) {
          if (e.target === replyDeleteModal) {
            replyDeleteModal.classList.add('hidden');
          }
        };
      }
    });
  };
});

// replyDeleteModalのキャンセルボタンをクリックしたらmodalを閉じる
const replyDeleteCancelBtns = document.querySelectorAll('#replyDeleteCancelBtn');

replyDeleteCancelBtns.forEach((replyDeleteCancelBtn) => {
  replyDeleteCancelBtn.addEventListener('click', () => {
    replyDeleteModals.forEach((replyDeleteModal) => {
      replyDeleteModal.classList.add('hidden');
    });
  });
});
