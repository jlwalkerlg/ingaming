<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF token -->
    <?php if (isset($csrf_token)) : ?>
        <meta name="csrf-token" content="<?= $csrf_token ?>">
    <?php endif; ?>
    <title><?= (isset($title) ? h($title) . ' | ' : '') . SITE_NAME ?></title>
    <!-- Google fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,700">
    <!-- Custom style sheet -->
    <link rel="stylesheet" href="<?= Asset::get('css/style.css') ?>">
    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico">
</head>

<body>

    <!-- Navigation -->
    <?php include_once APP_ROOT . '/views/includes/nav.php' ?>

    <!-- Main content -->
    <?php $this->yield('body') ?>

    <!-- Footer -->
    <footer class="site-foot">
        <div class="container">
            <p class="site-foot-text">&copy; <?= date('Y') ?> inGAMING</p>
        </div>
    </footer>

    <!-- Foot -->
    <?php $this->yield('foot') ?>

    <!-- Scripts -->
    <?php foreach ($scripts ?? [] as $script) : ?>
        <script src="<?= Asset::get("js/{$script}.js") ?>"></script>
    <?php endforeach; ?>
    <script src="<?= Asset::get("js/vendors.js") ?>"></script>

</body>

</html>
