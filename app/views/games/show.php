<?php $this->extends('default') ?>

<!-- Header -->
<header id="showHead" class="show-head" data-bg-img="<?= URL_ROOT ?>uploads/product_images/product_<?= h($game->id) ?>/<?= h($game->cover_img) ?>">
    <div class="show-head-container container mt-nav">
        <div class="show-head-blurb">
            <h1 class="show-head-title"><?= h($game->title) ?></h1>
            <div class="show-head-text"><?= purify($game->blurb) ?></div>
            <a href="#details" id="viewProductBtn" class="btn btn-block btn-outline-primary">View Product</a>
        </div>
        <div class="show-head-trailer">
            <div class="flex-video">
                <iframe width="560" height="315" src="<?= h($game->trailer) ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</header>

<article id="details" class="show-details mb-5">
    <div class="container flex-sm">
        <div class="text-center mr-4-sm">
            <img src="<?= URL_ROOT ?>uploads/product_images/product_<?= h($game->id) ?>/<?= h($game->case_img) ?>" alt="<?= h($game->title) ?> case" class="game-case mb-2">
            <button id="buyNowBtn" class="btn btn-block btn-action mb-2" data-product-id="<?= h($game->id) ?>">Buy now</button>
            <button id="addToBasketBtn" class="btn btn-block btn-primary mb-4" data-product-id="<?= h($game->id) ?>">Add to basket</button>
        </div>
        <div class="flex-1">
            <p class="text-bold">Product description</p>
            <div><?= purify($game->description) ?></div>
            <p class="text-bold">Product details</p>
            <dl class="dlist-bare">
                <div class="mb-2">
                    <dt>Platform:</dt>
                    <dd><?= h($game->platform) ?></dd>
                </div>
                <div class="mb-2">
                    <dt>Release date:</dt>
                    <dd><?= h($game->release_date) ?></dd>
                </div>
                <div class="mb-2">
                    <dt>Price:</dt>
                    <dd>£<span id="productPrice"><?= h($game->price) ?></span></dd>
                </div>
            </dl>
        </div>
    </div>
</article>
