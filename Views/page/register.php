<div>
    <div>
        <div>
            <h2>Register</h2>
            <form action="form/register" method="post">
                <!-- フォームがcsrfトークンを使用するようになりました。 -->
                <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken() ?>">
                <div>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="email"">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="company"">Company</label>
                    <input type="text" id="company" name="company">
                </div>
                <div>
                    <label for="password"">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <label for="confirm-password"">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>
                </div>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
</div>