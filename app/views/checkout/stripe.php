<?php $this->extends('default') ?>

<div class="full-screen flex align-center">
    <div class="container-slim flex-1 mt-nav">

        <form id="checkoutForm" action="<?= url('/api/checkout/confirm') ?>" class="form-full-screen my-3" method="POST" data-public-key="<?= STRIPE_PUBLIC_KEY ?>">

            <!-- CSRF token -->
            <?= CSRF::input() ?>

            <!-- Cardholder Name -->
            <div class="form-group mb-4">
                <label for="cardName" class="sr-only">Cardholder Name</label>
                <input id="cardName" type="text" name="card_name" class="form-input form-input-block" placeholder="Cardholder Name">
            </div>

            <!-- Email -->
            <div class="form-group mb-4">
                <label for="email" class="sr-only">Email</label>
                <input id="email" type="email" name="email" class="form-input form-input-block" placeholder="Email">
            </div>

            <!-- Address Line 1 -->
            <div class="form-group mb-4">
                <label for="addressLine1" class="sr-only">Address 1</label>
                <input id="addressLine1" type="text" name="address[line1]" class="form-input form-input-block" placeholder="Address 1">
            </div>

            <!-- Address Line 2 -->
            <div class="form-group mb-4">
                <label for="addressLine2" class="sr-only">Address 2</label>
                <input id="addressLine2" type="text" name="address[line2]" class="form-input form-input-block" placeholder="Address 2">
            </div>

            <!-- Address City -->
            <div class="form-group mb-4">
                <label for="addressCity" class="sr-only">City</label>
                <input id="addressCity" type="text" name="address[city]" class="form-input form-input-block" placeholder="City">
            </div>

            <!-- Address Country -->
            <div class="form-group mb-4">
                <label for="addressCountry" class="sr-only">Country</label>
                <input id="addressCountry" type="text" name="address[country]" class="form-input form-input-block" placeholder="Country">
            </div>

            <!-- Address Post Code -->
            <div class="form-group mb-4">
                <label for="addressPostCode" class="sr-only">Post Code</label>
                <input id="addressPostCode" type="text" name="address[postal_code]" class="form-input form-input-block" placeholder="Post Code">
            </div>

            <!-- Card Details -->
            <div class="mb-4">
                <div id="cardElement"></div>
            </div>

            <!-- Submit -->
            <div>
                <input id="submitBtn" type="submit" value="Submit Payment" class="btn btn-block btn-pill btn-primary">
            </div>

    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
