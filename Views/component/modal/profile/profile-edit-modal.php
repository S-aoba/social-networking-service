<!-- PostDeleteModal -->
<div id="profile-edit-modal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <form id="update-profile-form" method="POST" class="py-3 bg-white w-11/12 lg:w-[600px] h-fit flex flex-col rounded-md overflow-auto">
    <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
    <div class="flex items-center justify-between px-5 pb-3">
      <div class="flex items-center space-x-3">
        <img class="h-8 w-8" src="/images/close.svg" alt="閉じる">
        <h3 class="font-semibold">プロフィールを編集</h3>
      </div>
      <div>
        <button type="submit" class="bg-black px-4 py-1 text-white rounded-full text-sm">保存</button>
      </div>
    </div>
    <div class="relative">
      <?php require 'Views/component/modal/profile/profile-header.php' ?>
      <?php require 'Views/component/modal/profile/profile-user-image.php' ?>
    </div>
    <!-- User Info -->
    <?php require 'Views/component/modal/profile/profile-user-info.php' ?>
  </form>
</div>
<script>
  const profileEditBtn = document.getElementById('profileEditBtn');
  const profileEditModal = document.getElementById('profile-edit-modal');

  if (profileEditBtn) {
    profileEditBtn.addEventListener('click', () => {
      profileEditModal.classList.remove('hidden');
    });
  }
  const cancelBtns = document.getElementById('profileEditCancelBtn');

  cancelBtns.addEventListener('click', () => {
    profileEditModal.classList.add('hidden');
  });

  window.onclick = function(event) {
    if (event.target == profileEditModal) {
      profileEditModal.classList.add('hidden');
    }
  };

  const changePreviewImage = (inputId, previewId) => {
    document.getElementById(inputId).addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        if (previewId === 'blank-preview-header-image') {
          document.getElementById(previewId).classList.remove('hidden');
        }
        reader.onload = function(e) {
          document.getElementById(previewId).setAttribute('src', e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  changePreviewImage('header-image', 'preview-header-image')
  changePreviewImage('header-image', 'blank-preview-header-image')
  changePreviewImage('profile-image', 'preview-profile-image')


  // User情報の更新
  const updateProfileForm = document.getElementById('update-profile-form');

  updateProfileForm.addEventListener('submit', function(event) {
    // デフォルトのフォーム送信を防止します
    event.preventDefault();

    const updateProfileFormData = new FormData(updateProfileForm);

    fetch('/form/update/profile', {
      method: 'POST',
      body: updateProfileFormData
    }).then((res) => {
      console.log(res.status);
    })
  });
</script>
