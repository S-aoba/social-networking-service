const deletePostForm = document.getElementById('#deletePostForm');

if (deletePostForm) {
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
            location.href = '/home';
          }, 1000);
        } else if (data.success === 'error') {
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      });
    });
  });
}
