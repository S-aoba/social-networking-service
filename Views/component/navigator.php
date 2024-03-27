<div class="col-span-1 h-full pr-4 md:pr-6 border-r border-slate-200">
  <div class="flex flex-col items-center">
    <div class="space-y-2">
      <!-- LOGO -->
      <div class="py-4 flex justify-center">
        <img src="/images/logo.svg" alt="logo" width="35" height="35">
      </div>
      <!-- navigation item -->
      <a href="/home" class="flex flex-row items-center justify-start">
        <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
          <img src="/images/home.svg" alt="home">
        </div>
        <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
          <img src="/images/home.svg" alt="home" width="24" height="24">
          <p class="hidden lg:block text-lg">
            Home
          </p>
        </div>
      </a>
      <?php if ($user) : ?>
        <div class="flex flex-row items-center justify-start">
          <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
            <img src="/images/notification.svg" alt="notification">
          </div>
          <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
            <!-- Notificationが存在していれば、bg-slate-300の丸い印をつける -->
            <?php if ($is_notification_exists) : ?>
              <div class="h-4 w-4 bg-slate-300 rounded-full absolute top-3 left-4"></div>
            <?php endif; ?>
            <img src="/images/notification.svg" alt="notification" width="24" height="24">
            <p class="hidden lg:block text-lg">
              Notification
            </p>
          </div>
        </div>
        <div class="flex flex-row items-center justify-start">
          <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
            <img src="/images/message.svg" alt="message">
          </div>
          <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
            <img src="/images/message.svg" alt="message" width="24" height="24">
            <p class="hidden lg:block text-lg">
              Message
            </p>
          </div>
        </div>
        <div class="flex flex-row items-center justify-start ">
          <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
            <img src="/images/profile.svg" alt="profile">
          </div>
          <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
            <img src="/images/profile.svg" alt="profile" width="24" height="24">
            <p class="hidden lg:block text-lg">
              Profile
            </p>
          </div>
        </div>
        <form action="logout" method="POST" class="flex flex-row items-center justify-start">
          <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
          <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
            <img src="/images/logout.svg" alt="logout">
          </div>
          <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
            <img src="/images/logout.svg" alt="logout" width="24" height="24">
            <p class="hidden lg:block text-lg">
              Logout
            </p>
          </div>
        </form>
      <?php else : ?>
        <div id="loginBtn" class="flex flex-row items-center justify-start">
          <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
            <img src="/images/login.svg" alt="login">
          </div>
          <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
            <img src="/images/login.svg" alt="login" width="24" height="24">
            <p class="hidden lg:block text-lg">
              Login
            </p>
          </div>
        </div>
        <div id="registerBtn" class="flex flex-row items-center justify-start">
          <div class="
        relative
        rounded-full
        h-16
        w-16
        flex
        items-center
        justify-center
        p-4
        hover:bg-slate-200
        cursor-pointer
        lg:hidden
        transition-colors
        duration-300
        ">
            <img src="/images/register.svg" alt="register">
          </div>
          <div class="
        relative
        hidden
        lg:flex
        gap-4
        p-4
        rounded-full
        items-center
        w-full
        hover:bg-slate-200
        cursor-pointer
        transition-colors
        duration-300
        ">
            <img src="/images/register.svg" alt="register" width="24" height="24">
            <p class="hidden lg:block text-lg">
              Register
            </p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- LoginModal -->
<div id="loginModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <!-- Modal Content -->
  <div class="bg-white p-8 rounded-lg w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6">Login</h2>
    <form action="form/login" method="POST">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
        <input type="email" id="email" name="email" autocomplete="email" class="mt-1 p-2 block w-full border border-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" autocomplete="new-password" class="mt-1 p-2 block w-full border border-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div class="flex justify-end">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Login</button>
      </div>
    </form>
  </div>
</div>

<!-- RegisterModal -->
<div id="registerModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex justify-center items-center">
  <!-- Modal Content -->
  <div class="bg-white p-8 rounded-lg w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-6">Register</h2>
    <form action="form/register" method="POST">
      <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
      <!-- <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" id="username" name="username" class="mt-1 p-2 block w-full border border-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div> -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
        <input type="email" id="email" name="email" autocomplete="email" class="mt-1 p-2 block w-full border border-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" autocomplete="new-password" class="mt-1 p-2 block w-full border border-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div class="mb-4">
        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password" class="mt-1 p-2 block w-full border border-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div class="flex justify-end">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Register</button>
      </div>
    </form>
  </div>
</div>



<script>
  const loginModal = document.getElementById('loginModal');
  const loginBtn = document.getElementById('loginBtn');

  loginBtn.onclick = function() {
    loginModal.classList.remove('hidden');
  };

  const registerModal = document.getElementById('registerModal');
  const registerBtn = document.getElementById('registerBtn');

  registerBtn.onclick = function() {
    registerModal.classList.remove('hidden');
  };

  // Close modals when clicking outside of them
  window.onclick = function(event) {
    if (event.target == loginModal) {
      loginModal.classList.add('hidden');
    }
    if (event.target == registerModal) {
      registerModal.classList.add('hidden');
    }
  };
</script>
