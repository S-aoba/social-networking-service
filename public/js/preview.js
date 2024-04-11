const preview = document.getElementById('preview');
const image = document.getElementById('image');

if (preview && image) {
  image.addEventListener('change', function () {
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
}
