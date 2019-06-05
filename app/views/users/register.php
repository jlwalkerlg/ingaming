<?php $this->extends('default') ?>

<div class="full-screen flex align-center">
    <div class="container-slim flex-1 mt-nav">

        <h1 class="h4 mb-3">Register</h1>

        <form action="<?= url('register') ?>" class="form-full-screen" method="POST">
            <?= CSRF::input() ?>

            <!-- First name -->
            <div class="mb-3">
                <label for="firstName" class="sr-only">First name</label>
                <input type="text" name="first_name" id="firstName" class="form-input form-input-block" placeholder="First name" value="<?= h(old('first_name')) ?>">
                <?= formError($errors['first_name'] ?? '') ?>
            </div>

            <!-- Last name -->
            <div class="mb-3">
                <label for="lastName" class="sr-only">Last name</label>
                <input type="text" name="last_name" id="lastName" class="form-input form-input-block" placeholder="Last name" value="<?= h(old('last_name') ?? '') ?>">
                <?= formError($errors['last_name'] ?? '') ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="sr-only">Email</label>
                <input type="email" name="email" id="email" class="form-input form-input-block" placeholder="Email Address" value="<?= h(old('email') ?? '') ?>">
                <?= formError($errors['email'] ?? '') ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" class="form-input form-input-block" placeholder="Password">
                <?= formError($errors['password'] ?? '') ?>
            </div>

            <!-- Confirm password -->
            <div class="mb-3">
                <label for="confirmFassword" class="sr-only">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirmPassword" class="form-input form-input-block" placeholder="Confirm Password">
                <?= formError($errors['confirm_password'] ?? '') ?>
            </div>

            <input type="submit" value="Register" class="btn btn-block btn-primary">
        </form>

        <p class="text-right">
            <a href="#">Forgot your password?</a>
        </p>

    </div>
</div>
