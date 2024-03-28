const postForm = document.getElementById('postForm');

postForm.addEventListener('submit', function (event) {
  event.preventDefault();

  const formData = new FormData(postForm);

  fetch('/form/post', {
    method: 'POST',
    body: formData,
  }).then((response) => {
    response.json().then((data) => {
      if (data.status === 'success') {
        postForm.reset();
        // 画面を更新する（遅延させる）
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
      //TODO: エラーの場合の処理は必要なのか
      else if (data.success === 'error') {
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
    });
  });
});

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





