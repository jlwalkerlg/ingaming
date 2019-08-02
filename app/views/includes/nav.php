<nav id="siteNav" class="site-nav">
    <div class="site-nav-container container">

        <!-- branding -->
        <a href="<?= url('/') ?>" class="site-nav-brand">
            <span>inGAMING</span>
        </a>

        <!-- admin -->
        <?php if (Auth::isLoggedIn() && Auth::user()->isAdmin()) : ?>
            <a href="<?= url('admin') ?>" class="site-nav-admin">
                <span>Dashboard</span>
            </a>
        <?php endif; ?>

        <!-- #siteNavList -->
        <ul id="siteNavList" class="site-nav-list">
            <li class="site-nav-item"><a href="<?= url('/') ?>" class="site-nav-link"><span>Home</span></a></li>
            <li class="site-nav-item"><a href="<?= url('games') ?>" class="site-nav-link"><span>Games</span></a></li>

            <?php if (!Auth::isLoggedIn()) : ?>
                <li class="site-nav-item"><a href="<?= url('login') ?>" class="site-nav-link"><span>Login</span></a></li>
                <li class="site-nav-item"><a href="<?= url('register') ?>" class="site-nav-link"><span>Sign Up</span></a></li>
            <?php else : ?>
                <li class="site-nav-item"><a href="<?= url('logout') ?>" class="site-nav-link"><span>Logout</span></a></li>
            <?php endif; ?>
        </ul>

        <!-- cart -->
        <div class="site-nav-cart">
            <!-- cart toggle button -->
            <button id="siteNavCartToggle" class="site-nav-cart-btn" aria-label="Sohw/hide shopping cart" aria-expanded="false">
                <?php include PUBLIC_ROOT . '/img/icons/cart.svg'; ?>
                <span id="cartItems"><?= h($cartCount ?? '0') ?></span>
            </button>

            <!-- cart dropdown -->
            <div id="siteNavCartDropdown" class="site-nav-cart-dropdown">
                <p class="h4 m-0">Cart</p>
                <p class="my-1">
                    <span class="text-bold mr-1">Items:</span> <span id="cartDropdownItems"><?= h($cartCount ?? '0') ?></span>
                </p>
                <p class="my-1">
                    <span class="text-bold mr-1">Total:</span> £<span id="cartDropdownTotal"><?= h($cartTotal ?? '0.00') ?></span>
                </p>
                <hr>
                <a id="cartLink" href="<?= url('cart/') ?>" class="btn btn-block btn-action">View Cart</a>
            </div>
        </div>

        <!-- site nav toggle button -->
        <button id="siteNavToggle" class="site-nav-toggle hamburger" aria-label="Show/hide site navigation" aria-expanded="false" aria-controls="siteNavList">
            <div class="hamburger-bar"></div>
            <div class="hamburger-bar"></div>
            <div class="hamburger-bar"></div>
        </button>

    </div>
</nav>

<?php if ($alert = Session::get('alert')) : ?>
    <p id="alert" class="alert"><?= h($alert) ?></p>
<?php endif; ?>
