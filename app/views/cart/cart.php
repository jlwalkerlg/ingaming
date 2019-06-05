<?php $this->extends('default') ?>

<div class="full-screen">
    <div class="container mt-nav hide-overflow">

        <h1 class="h4 mt-5">Shopping Cart</h1>

        <div class="flex-md mt-5">

            <?php if (empty($products)) : ?>
                <p class="flex-1">You have no items in your shopping cart. <a href="<?= url('/games') ?>" class="text-link">Get shopping!</a></p>
            <?php else : ?>
                <!-- Products table -->
                <table id="productsTable" class="cart flex-1 mr-4">
                    <thead class="sr-only">
                        <tr>
                            <th>Product image</th>
                            <th>Product name</th>
                            <th>Product price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Product row -->
                        <?php foreach ($products as $product) : ?>
                            <tr id="product<?= h($product->id) ?>" class="cart-row">
                                <td class="cart-img-cell">
                                    <div class="cart-img-wrap">
                                        <img src="<?= URL_ROOT ?>uploads/product_images/product_<?= h($product->game_id) ?>/<?= h($product->case_img) ?>" alt="<?= h($product->title) ?>" width="66" height="83">
                                        <form action="#" method="POST" class="cart-remove-btn" data-product-id="<?= h($product->id) ?>">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="submit" value="Remove" class="btn-plain">
                                        </form>
                                    </div>
                                </td>
                                <td class="cart-name"><a href="<?= url('games/' . h($product->game_id)) ?>" class="text-link"><?= h($product->title) ?></a></td>
                                <td class="cart-price">£<?= h($product->price) ?></td>
                                <td class="cart-quantity">
                                    <form action="#" method="POST">
                                        <input data-product-id="<?= h($product->id) ?>" type="number" name="quantity" step="1" min="1" max="999" class="form-input cart-quantity" value="<?= h($product->quantity) ?>">
                                        <input type="submit" value="Submit" class="sr-only">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Checkout aside box -->
                <aside id="checkout" class="aside mb-4 align-self-start">
                    <h2 class="h4">Checkout</h2>
                    <dl class="dlist-bare">
                        <div class="mb-3">
                            <dt>Products:</dt>
                            <dd id="checkoutPrice" class="text-bold">£<?= h($totalPrice) ?></dd>
                        </div>
                        <div class="mb-3">
                            <dt>Shipping:</dt>
                            <dd class="text-bold">£0.00</dd>
                        </div>
                        <div class="mb-3">
                            <dt>Subtotal:</dt>
                            <dd id="checkoutSubtotal" class="text-bold">£<?= h($subtotal) ?></dd>
                        </div>
                    </dl>
                    <hr>
                    <a href="<?= url('/checkout') ?>" class="btn btn-block btn-action text-small">Proceed to checkout</a>
                </aside>

            <?php endif; ?>

        </div>

    </div>
</div>
