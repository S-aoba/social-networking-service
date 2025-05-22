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
            <button type="submit" class="text-sm py-2 px-3 bg-slate-800/50 text-white rounded-md hover:cursor-pointer hover:bg-slate-800 focus:bg-slate-800 transition duration-300 disabled:pointer-events-none">Register</button>
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
        
        registerForm.addEventListener('submit', async(e) => {
            e.preventDefault();

            const formData = new FormData(registerForm);
            errorMessage.classList.add('hidden');
            errorMessage.textContent = '';

            const res = await fetch('api/register', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            
            if(data.status === 'success') window.location.href = data.redirect;
            else {
                let msg = '';
                errorMessage.textContent = data.message;
                errorMessage.classList.remove('hidden');
            }
        })
    })
</script>