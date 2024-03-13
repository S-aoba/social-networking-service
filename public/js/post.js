document.getElementById('image').addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    if (file.type.startsWith('video/')) {
      // ファイルが動画であるかを確認
      const reader = new FileReader();
      reader.onload = function (e) {
        // プレビューを表示する処理
        const videoElement = document.getElementById('preview-video');
        videoElement.setAttribute('src', e.target.result);
        videoElement.setAttribute('controls', ''); // controls属性を追加
      };
      reader.readAsDataURL(file);
    } else {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('preview-image').setAttribute('src', e.target.result);
      };
      reader.readAsDataURL(file);
    }
  }
});

const tweetForm = document.getElementById('postForm');

tweetForm.addEventListener('submit', function (event) {
  event.preventDefault();

  const formData = new FormData(tweetForm);

  fetch('form/post', {
    method: 'POST',
    body: formData,
  }).then((response) => {
    response.json().then((data) => {
      if (data.status === 'success') {
        tweetForm.reset();
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

    fetch('form/post/delete', {
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

const unLikeForms = document.querySelectorAll('#unlikeForm');

unLikeForms.forEach((unLikeForm) => {
  unLikeForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const unLikeFormData = new FormData(unLikeForm);

    fetch('form/unlike', {
      method: 'POST',
      body: unLikeFormData,
    }).then((res) => {
      res.json().then((data) => {
        console.log(data.status);
      });
    });
  });
});

const LikeForms = document.querySelectorAll('#likeForm');

LikeForms.forEach((LikeForm) => {
  LikeForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const LikeFormData = new FormData(LikeForm);

    fetch('form/like', {
      method: 'POST',
      body: LikeFormData,
    }).then((res) => {
      res.json().then((data) => {
        console.log(data.status);
      });
    });
  });
});
