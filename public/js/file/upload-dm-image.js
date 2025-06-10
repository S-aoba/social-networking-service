  document.addEventListener('DOMContentLoaded', function() {
  const dmUploadInput = document.getElementById('dm-upload-file');

  dmUploadInput.addEventListener('change', function (event) {
    const file = event.target.files[0];

    if(file === false) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      const imgPreviewArea = document.getElementById('dm-image-preview-area');
      const previewImg = document.createElement('img');
      
      previewImg.src = e.target.result;
      previewImg.alt = 'dm-image-preview';
      previewImg.classList.add('size-40');

      imgPreviewArea.classList.add('mb-2');
      imgPreviewArea.appendChild(previewImg);
    }
    reader.readAsDataURL(file);
  })
})
