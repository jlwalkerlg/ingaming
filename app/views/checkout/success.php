<?php $this->extends('default') ?>

<div class="container full-screen text-center">
    <div class="mt-nav hide-overflow">
        <h1>Thank You!</h1>
        <p class="h4">Your purchase of £<?= h($transaction->amount) ?> was successful.</p>
        <p>Your order will be shipped to the following address:</p>
        <address>
            <p class="m-0"><?= h($transaction->address_1) ?>,</p>
            <?php if ($transaction->address_2) : ?>
                <p class="m-0"><?= h($transaction->address_2) ?>,</p>
            <?php endif; ?>
            <p class="m-0"><?= h($transaction->city) ?>,</p>
            <p class="m-0"><?= h($transaction->country) ?>,</p>
            <p class="m-0"><?= h($transaction->postcode) ?></p>
        </address>
        <p>An email verification will be sent to <i><?= h($transaction->email) ?></i> shortly.</p>
    </div>
</div>
