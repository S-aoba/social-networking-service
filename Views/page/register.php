<div class="w-full h-full flex flex-col justify-center items-center">
    <div class="w-full py-5 text-center">
        <h2 class="text-2xl font-semibold">Register</h2>
    </div>
    <div class="w-1/2 p-10 border border-slate-200 rounded">
        <div id="register-error-message" class="hidden my-2 py-2 text-center text-red-600 bg-red-100 rounded-lg"></div>
        <form id="register-form" method="post" class="flex flex-col space-y-4">
            <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
            <div class="w-full flex flex-col space-y-2">
                <label for="username"">Username</label>
                <input class="text-sm p-2 border border-slate-200 rounded-md focus:outline-none" type="text" id="username" name="username" required>
            </div>
            <div class="w-full flex flex-col space-y-2">
                <label for="email"">Email</label>
                <input class="text-sm p-2 border border-slate-200 rounded-md focus:outline-none" type="email" id="email" name="email" required>
            </div>
            <div class="w-full flex flex-col space-y-2">
                <label for="password"">Password</label>
                <input class="text-sm p-2 border border-slate-200 rounded-md focus:outline-none" type="password" id="password" name="password" required>
            </div>
            <div class="w-full flex flex-col space-y-2">
                <label for="confirm-password"">Confirm Password</label>
                <input class="text-sm p-2 border border-slate-200 rounded-md focus:outline-none" type="password" id="confirm-password" name="confirm_password" required>
            </div>
            <button id="register-btn" type="submit" class="min-h-10 text-sm py-2 px-3 bg-slate-800/50 text-white rounded-md hover:cursor-pointer hover:bg-slate-800 focus:bg-slate-800 transition duration-300 disabled:pointer-events-none">Register</button>
        </form>
        <div class="mt-4 w-full text-end">
            <a href="/login" class="text-sm hover:underline hover:underline-offset-4">Have you a account? Login</a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('register-form');
        const errorMessage = document.getElementById('register-error-message');

        const registerBtn = document.getElementById('register-btn');

        const loadingImg = document.createElement('img');
        loadingImg.src = '/images/loading-icon.svg';
        loadingImg.alt = 'loading';
        loadingImg.classList.add('animate-spin');
        
        function showLoading() {
            registerBtn.innerHTML = '';
            registerBtn.appendChild(loadingImg);
            registerBtn.classList.add('flex', 'items-center', 'justify-center');
        }

        function resetButton() {
            registerBtn.innerHTML = 'Register';
            registerBtn.classList.remove('flex', 'items-center', 'justify-center');
        }

        function showError(message) {
            errorMessage.innerHTML = '';
            if (typeof message === 'string') {
                errorMessage.textContent = message;
            } else if (typeof message === 'object') {
                const ul = document.createElement('ul');
                Object.keys(message).forEach((key) => {
                    const li = document.createElement('li');
                    li.classList.add('list-none');
                    li.innerText = message[key];
                    ul.appendChild(li);
                });
                errorMessage.appendChild(ul);
            } else {
                errorMessage.textContent = 'Something went wrong on our end. Please try again later.';
            }
            errorMessage.classList.remove('hidden');
        }
        
        registerForm.addEventListener('submit', async(e) => {
            e.preventDefault();

            const formData = new FormData(registerForm);
            errorMessage.classList.add('hidden');
            errorMessage.textContent = '';

            showLoading();

            const res = await fetch('api/register', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            
            if(data.status === 'success') {
                window.location.href = data.redirect;
            } else {
                showError(data.message);
                resetButton();
            }
        })
    })
</script>