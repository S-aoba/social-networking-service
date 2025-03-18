<div>
    <div>
        <div>
            <h2>Login</h2>
            <form action="form/login" method="post">
                <input type="hidden" name="csrf_token" value="<?= Helpers\CrossSiteForgeryProtection::getToken(); ?>">
                <div -3">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div -3">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</div>