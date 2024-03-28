const unLikeForms = document.querySelectorAll('#unlikeForm');

unLikeForms.forEach((unLikeForm) => {
  unLikeForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const unLikeFormData = new FormData(unLikeForm);

    fetch('/form/unlike', {
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

    fetch('/form/like', {
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
