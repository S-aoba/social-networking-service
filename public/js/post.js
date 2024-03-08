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
    });
  });
});
