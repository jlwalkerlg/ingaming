<?php $this->extends('default') ?>

<div class="full-screen flex align-center">
    <div class="container-slim flex-1 mt-nav">

        <h1 class="h4 mb-3">Login</h1>

        <form action="<?= url('login') ?>" class="form-full-screen" method="POST">
            <?= CSRF::input() ?>
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id="email" class="form-input form-input-block mb-3" placeholder="Email Address">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="form-input form-input-block mb-3" placeholder="Password">
            <input type="submit" value="Login" class="btn btn-block btn-primary">
        </form>

    </div>
</div>
