document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('image').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('preview-image').setAttribute('src', e.target.result);
      };
      reader.readAsDataURL(file);
    }
  });

  // プロフィール画像以外の更新アクション
  const form = document.getElementById('update-profile-form');

  form.addEventListener('submit', function (event) {
    // デフォルトのフォーム送信を防止します
    event.preventDefault();

    // FormDataオブジェクトを作成し、コンストラクタにフォームを渡してすべての入力値を取得します
    const formData = new FormData(form);

    // fetchリクエストを送信します
    fetch('/form/update/profile', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json()) // レスポンスからJSONを解析します
      .then((data) => {
        // サーバからのレスポンスデータを処理します
        if (data.status === 'success') {
          // 成功メッセージを表示したり、リダイレクトしたり、コンソールにログを出力する可能性があります
          console.log(data.message);
          alert('Update successful!');
          if (!formData.has('id')) form.reset();
        } else if (data.status === 'error') {
          // ユーザーにエラーメッセージを表示します
          console.error(data.message);
          alert('Update failed: ' + data.message);
        }
      })
      .catch((error) => {
        // ネットワークエラーかJSONの解析エラー
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
      });
  });

  // プロフィール画像の更新アクション
  const imageForm = document.getElementById('update-profile-image-form');

  imageForm.addEventListener('submit', function (event) {
    // デフォルトのフォーム送信を防止します
    event.preventDefault();

    // FormDataオブジェクトを作成し、コンストラクタにフォームを渡してすべての入力値を取得します
    const formData = new FormData(imageForm);
    console.log(imageForm);
    // fetchリクエストを送信します
    fetch('/form/update/profile-image', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.json()) // レスポンスからJSONを解析します
      .then((data) => {
        // サーバからのレスポンスデータを処理します
        if (data.status === 'success') {
          // 成功メッセージを表示したり、リダイレクトしたり、コンソールにログを出力する可能性があります
          console.log(data.message);
          alert('Update successful!');
          if (!formData.has('id')) imageForm.reset();
        } else if (data.status === 'error') {
          // ユーザーにエラーメッセージを表示します
          console.error(data.message);
          alert('Update failed: ' + data.message);
        }
      })
      .catch((error) => {
        // ネットワークエラーかJSONの解析エラー
        console.error('Error:', error);
        alert('An error occurred. P.');
      });
  });
});
