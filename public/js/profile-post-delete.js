function toggleMenu(menu) {
  // 対応するメニューを取得
  var menuDiv = menu.querySelector('.menu');

  // メニューの表示状態を切り替え
  if (menuDiv.classList.contains('hidden')) {
    // 非表示状態なら表示する
    menuDiv.classList.remove('hidden');
    menuDiv.classList.add('z-20');
  } else {
    // 表示状態なら非表示にする
    menuDiv.classList.add('hidden');
  }
}
const postMenus = document.querySelectorAll('#post-menu');

postMenus.forEach((postMenu) => {
  postMenu.addEventListener('click', () => {
    // クリックされたpost-menu内のmenuを取得
    const menu = postMenu.querySelector('#menu');

    // 表示状態を切り替える
    menu.classList.toggle('hidden');
  });
});

// DeleteModal
const postDeleteModal = document.getElementById('postDeleteModal');
const deleteBtns = document.querySelectorAll('#deleteBtn');

// postDeleteModalを開く
deleteBtns.forEach((deleteBtn) => {
  deleteBtn.onclick = function () {
    postDeleteModal.classList.remove('hidden');
  };
});

// postDeleteModalのキャンセルボタンをクリックしたらmodalを閉じる
const cancelBtns = document.querySelectorAll('#postDeleteCancelBtn');

cancelBtns.forEach((btn) => {
  btn.addEventListener('click', () => {
    postDeleteModal.classList.add('hidden');
  });
});

// postDeleteModal外をクリックしたらmodalを閉じる
window.onclick = function (e) {
  if (e.target == postDeleteModal) {
    postDeleteModal.classList.add('hidden');
  }
};

const deletePostForms = document.querySelectorAll('#deletePostForm');

deletePostForms.forEach((deletePostForm) => {
  deletePostForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const deletePostFormData = new FormData(deletePostForm);

    fetch('/form/post/delete', {
      method: 'POST',
      body: deletePostFormData,
    }).then((res) => {
      res.json().then((data) => {
        if (data.status === 'success') {
          setTimeout(() => {
            location.reload();
          }, 1000);
        } else if (data.success === 'error') {
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      });
    });
  });
});
