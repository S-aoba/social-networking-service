const preview = document.getElementById('preview');

document.getElementById('image').addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      if (file.type.startsWith('video/')) {
        // 動画の場合
        const videoElement = document.createElement('video');
        videoElement.setAttribute('id', 'preview-video');
        videoElement.setAttribute('src', e.target.result);
        videoElement.setAttribute('controls', ''); // controls属性を追加
        preview.innerHTML = ''; // preview内の要素をクリア
        preview.appendChild(videoElement);
      } else {
        // 画像の場合
        const imageElement = document.createElement('img');
        imageElement.setAttribute('id', 'preview-image');
        imageElement.setAttribute('src', e.target.result);
        imageElement.setAttribute('class', 'object-cover max-h-96');
        preview.innerHTML = ''; // preview内の要素をクリア
        preview.appendChild(imageElement);
      }
    };
    reader.readAsDataURL(file);
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

const replyForms = document.querySelectorAll('#replyForm');

replyForms.forEach((replyForm) => {
  replyForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const replyFormData = new FormData(replyForm);

    fetch('form/reply', {
      method: 'POST',
      body: replyFormData,
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

const deleteReplyForms = document.querySelectorAll('#deleteReplyForm');

deleteReplyForms.forEach((deleteReplyForm) => {
  deleteReplyForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const deleteReplyFormData = new FormData(deleteReplyForm);

    fetch('form/reply/delete', {
      method: 'POST',
      body: deleteReplyFormData,
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
