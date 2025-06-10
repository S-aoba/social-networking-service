document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('modal');
  const editProfileButton = document.getElementById('edit-profile-button');
  const usernameInput = document.getElementById('username');
    
  editProfileButton.addEventListener('click', () => {
    if(modal.classList.contains('hidden')) {
      modal.classList.remove('hidden');
      usernameInput.focus();
    } else {
      modal.classList.add('hidden');
    }
  });  
});