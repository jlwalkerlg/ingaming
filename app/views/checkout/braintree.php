<?php $this->extends('default') ?>

<div class="container full-screen">
    <div class="container mt-nav hide-overflow">

        <h1 class="h3">Checkout</h1>

        <div class="flex-md">

            <div class="flex-3 mr-4-md">

                <form action="<?= url('checkout') ?>" method="post" id="checkoutForm" data-client-token="<?= $clientToken ?>" class="mb-5">

                    <!-- CSRF -->
                    <?= CSRF::input() ?>

                    <!-- Nonce -->
                    <input type="hidden" name="payment_method_nonce" id="nonce">

                    <!-- Cardholder Name -->
                    <div class="form-group mb-4">
                        <label for="cardName" class="sr-only">Cardholder Name</label>
                        <input id="cardName" type="text" name="name" class="form-input form-input-block" placeholder="Cardholder Name" value="<?= h(old('name') ?? '') ?>">
                        <?= formError($errors['name'] ?? '') ?>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-4">
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" type="email" name="email" class="form-input form-input-block" placeholder="Email" value="<?= h(old('email') ?? '') ?>">
                        <?= formError($errors['email'] ?? '') ?>
                    </div>

                    <!-- Address Line 1 -->
                    <div class="form-group mb-4">
                        <label for="addressLine1" class="sr-only">Address 1</label>
                        <input id="addressLine1" type="text" name="address[line1]" class="form-input form-input-block" placeholder="Address 1" value="<?= h(old('address.line1') ?? '') ?>">
                        <?= formError($errors['address.line1'] ?? '') ?>
                    </div>

                    <!-- Address Line 2 -->
                    <div class="form-group mb-4">
                        <label for="addressLine2" class="sr-only">Address 2</label>
                        <input id="addressLine2" type="text" name="address[line2]" class="form-input form-input-block" placeholder="Address 2" value="<?= h(old('address.line2') ?? '') ?>">
                        <?= formError($errors['address.line2'] ?? '') ?>
                    </div>

                    <!-- Address City -->
                    <div class="form-group mb-4">
                        <label for="addressCity" class="sr-only">City</label>
                        <input id="addressCity" type="text" name="address[city]" class="form-input form-input-block" placeholder="City" value="<?= h(old('address.city') ?? '') ?>">
                        <?= formError($errors['address.city'] ?? '') ?>
                    </div>

                    <!-- Address Country -->
                    <div class="form-group mb-4">
                        <label for="addressCountry" class="sr-only">Country</label>
                        <input id="addressCountry" type="text" name="address[country]" class="form-input form-input-block" placeholder="Country" value="<?= h(old('address.country') ?? '') ?>">
                        <?= formError($errors['address.country'] ?? '') ?>
                    </div>

                    <!-- Address Post Code -->
                    <div class="form-group mb-4">
                        <label for="addressPostCode" class="sr-only">Post Code</label>
                        <input id="addressPostCode" type="text" name="address[postal_code]" class="form-input form-input-block" placeholder="Post Code" value="<?= h(old('address.postal_code') ?? '') ?>">
                        <?= formError($errors['address.postal_code'] ?? '') ?>
                    </div>

                    <!-- Card -->
                    <div class="mb-4">
                        <div id="btDropin"></div>
                    </div>

                    <!-- Submit -->
                    <input type="submit" value="Submit" class="btn btn-primary btn-block">

                </form>

            </div>

            <div class="flex-2">
                <h2 class="h3">Items:</h2>
                <?php foreach ($products as $product) : ?>
                    <div class="flex align-start mb-3">
                        <div class="flex-1 mr-1">
                            <img src="<?= URL_ROOT ?>uploads/product_images/product_<?= h($product->id) ?>/<?= h($product->case_img) ?>" alt="<?= h($product->title) ?> case">
                        </div>
                        <div class="flex-5">
                            <p class="mt-0 mb-1"><?= h($product->title) ?> (<?= h($product->platform) ?>)</p>
                            <p class="mt-0 mb-1">x <?= h($product->quantity) ?></p>
                            <p class="mt-0 mb-1">£<?= h($product->price) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <h3 class="h4 my-1">Subtotal:</h3>
                <p id="subtotal" class="my-1">£<?= h($subtotal) ?></p>
            </div>

        </div>

    </div>
</div>
