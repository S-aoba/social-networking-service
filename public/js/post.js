const tweetForm = document.getElementById('post-form');

tweetForm.addEventListener('submit', function (event) {
  event.preventDefault();

  const formData = new FormData(tweetForm);

  fetch('form/post', {
    method: 'POST',
    body: formData,
  }).then((response) => {
    response.json().then((data) => {
      console.log(data);
      if (data.status === 'success') {
        alert(data.message);
        tweetForm.reset();
      }
    });
  });
});
