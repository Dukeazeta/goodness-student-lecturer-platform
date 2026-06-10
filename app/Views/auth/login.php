<section class="auth-page">
    <a class="auth-brand" href="<?= e(url('home')) ?>">
        <img class="brand-logo" src="<?= e(asset('img/classbridge-logo.png')) ?>" alt="ClassBridge">
        <span>ClassBridge</span>
    </a>

    <form class="auth-card" method="post" action="<?= e(url('login.attempt')) ?>">
        <?= csrf_field() ?>
        <div class="auth-heading">
            <h1>Sign in</h1>
            <p>Access your academic communication workspace.</p>
        </div>

        <label>
            <span>Email address</span>
            <input type="email" name="email" required autocomplete="email">
        </label>
        <label>
            <span>Password</span>
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button class="btn primary" type="submit">Sign in</button>
    </form>
</section>
