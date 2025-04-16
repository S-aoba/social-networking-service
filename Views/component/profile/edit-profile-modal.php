<div id="modal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
  
  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <form id="editProfileForm" method="POST" action="/form/update/profile" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
            <input type="hidden" name="user_id" value="<?= $profile->getUserId(); ?>">
            <div class="sm:flex-col sm:items-start">
              <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Edit Profile</h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">Please fill in the following fields to update your profile.</p>
              </div>
              <div class="mt-2 relative">
                <img src="<?= $imagePath ?>" alt="user-icon" class="size-20" id="previewImage">
                <label for="upload-file" class="absolute top-5 left-5 hover:cursor-pointer hover:bg-gray-100 p-2 rounded-full">
                  <img src="/images/camera.svg" alt="camera-icon">
                  <input type="file" id="upload-file" name="upload-file" value="<?php echo $profile->getImagePath() ?>" class="hidden">
                </label>
                </div>
              <div class="mt-2">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter your username" value="<?php echo $profile->getUsername() ?>">
              </div> 
              <div class="mt-2">
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" id="age" name="age" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter your age" value="<?php echo $profile->getAge() ?>">
              </div>
              <div class="mt-2">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" id="address" name="address" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter your address" value="<?php echo $profile->getAddress() ?>">
              </div>
              <div class="mt-2">
                <label for="hobby" class="block text-sm font-medium text-gray-700">Hobby</label>
                <input type="text" id="hobby" name="hobby" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter your hobby" value="<?php echo $profile->getHobby() ?>">
              </div>
              <div class="mt-2">
                <label for="self_introduction" class="block text-sm font-medium text-gray-700">Self Introduction</label>
                <textarea id="self_introduction" name="self_introduction" rows="4" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Enter your self introduction"><?php echo $profile->getSelfIntroduction() ?></textarea>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button id="updateBtn" type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 sm:ml-3 sm:w-auto">Submit</button>
            <button id="cancelBtn" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
          </div>
          </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal');
    const cancelBtn = document.getElementById('cancelBtn');

    cancelBtn.addEventListener('click', function() {
      modal.classList.add('hidden');
    });

    const imagePath = document.getElementById('image_path');
    imagePath.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.getElementById('previewImage');
          img.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  });

</script>