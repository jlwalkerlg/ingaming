<?php $this->extends('default') ?>

<!-- .site-head -->
<header id="siteHead" class="site-head" data-bg-img="<?= url('uploads/product_images/product_' . h($featured->id) . '/' . h($featured->cover_img)) ?>">

    <h1 class="sr-only"><?= h(SITE_NAME) ?></h1>

    <div class="site-head-container container text-center">
        <p class="site-head-title text-upper m-0"><?= h($featured->title) ?></p>
        <p class="site-head-subtitle mt-2 mb-4"><?= $featured->isOut() ? 'Out now on' : 'Coming soon to' ?> <?= h($featured->platform) ?></p>
        <div class="site-head-btns">
            <span class="site-head-btn m-1">
                <button id="buyNowBtn" class="btn btn-block btn-primary btn-pill" data-product-id="<?= h($featured->id) ?>"><?= $featured->isOut() ? 'Buy' : 'Preorder' ?> now</button>
            </span>
            <span class="site-head-btn m-1">
                <button id="trailerBtn" class="btn btn-block btn-outline-secondary btn-pill">Watch trailer</button>
            </span>
        </div>
        <div class="site-head-mouse">
            <p class="sr-only">Scroll down</p>
        </div>
    </div>

    <!-- YouTube iframe embed -->
    <div id="siteHeadModal" class="site-head-modal">
        <button id="modalClose" class="modal-close btn-plain"><span class="sr-only">Close</span></button>
        <div class="site-head-trailer">
            <div class="flex-video">
                <iframe id="yt-trailer" width="546" height="307" src="<?= h($featured->trailer) ?>?enablejsapi=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
        <a id="modalLastEl" href="#" aria-hidden="true"></a>
    </div>

</header>

<!-- #latest -->
<section id="latest" class="bg-dark hide-overflow">

    <?php foreach ($platforms as $platform) : ?>

        <article class="latest-games container">

            <h2 class="h3">Latest <?= h($platform->short_name) ?> Games</h2>

            <section class="game-carousel mb-3">

                <?php foreach ($games as $game) : ?>
                    <?php if ($platform->name !== $game->platform_name) continue; ?>
                    <a href="<?= url('games/' . h($game->id)) ?>" class="game">
                        <img src="<?= url('uploads/product_images/product_' . h($game->id) . '/' . h($game->case_img)) ?>" alt="<?= h($game->title) ?>" width="79" height="99" class="game-img">
                        <p class="game-title"><?= h($game->title) ?></p>
                        <div class="stars game-stars">
                            <?php for ($j = 0; $j < $game->rating; $j++) : ?>
                                <img src="<?= url('img/icons/star.svg') ?>" alt="star" class="star">
                            <?php endfor; ?>
                        </div>
                        <p class="game-price">£<?= h($game->price) ?></p>
                    </a>
                <?php endforeach; ?>

            </section>

            <a href="<?= url('games') ?>">Browse <?= h($platform->short_name) ?> games</a>

        </article>

    <?php endforeach; ?>

</section>
