const followForm = document.getElementById('follow-form');
if (followForm) {
  followForm.addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(followForm);

    fetch('/form/follow', {
      method: 'POST',
      body: formData,
    }).then((response) => {
      response.json().then((data) => {
        if (data.status === 'success') {
          console.log(data.message);
          alert('Follow successful!');
          if (!formData.has('id')) followForm.reset();
        } else if (data.status === 'error') {
          console.error(data.message);
          alert('Follow failed: ' + data.message);
        }
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      });
    });
  });
} else {
  const unFollowForm = document.getElementById('unfollow-form');
  unFollowForm.addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(unFollowForm);

    fetch('/form/unfollow', {
      method: 'POST',
      body: formData,
    }).then((response) => {
      response.json().then((data) => {
        if (data.status === 'success') {
          console.log(data.message);
          alert('Unfollow successful!');
          if (!formData.has('id')) unFollowForm.reset();
        } else if (data.status === 'error') {
          console.error(data.message);
          alert('Unfollow failed: ' + data.message);
        }
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      });
    });
  });
}
