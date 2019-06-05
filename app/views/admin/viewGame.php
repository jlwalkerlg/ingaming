<?php $this->extends('default') ?>

<div class="container mt-nav hide-overflow">
    <!-- Back -->
    <p class="mt-5"><a href="<?= url('/admin/games') ?>" class="text-link">Back to games</a></p>

    <!-- title -->
    <h1><?= h($game->title) ?></h1>

    <!-- Actions -->
    <div>
        <a href="<?= url('/admin/games/' . h($game->id) . '/edit') ?>" class="text-link">Edit</a>
        <form action="<?= url('/admin/games/' . $game->id) ?>" method="POST" class="delete-form" data-game-id="<?= h($game->id) ?>">
            <?= CSRF::input() ?>
            <?= spoofVerb('delete') ?>
            <input class="btn btn-plain text-link d-inline" type="submit" value="Delete">
        </form>
    </div>

    <!-- blurb -->
    <p class="text-bold">Blurb</p>
    <p><?= purify($game->blurb) ?></p>

    <!-- description -->
    <p class="text-bold">Description</p>
    <p><?= purify($game->description) ?></p>

    <!-- platform -->
    <p class="text-bold">Platform</p>
    <p><?= h($game->platform) ?></p>

    <!-- price -->
    <p class="text-bold">Price</p>
    <p><?= h($game->price) ?></p>

    <!-- release_date -->
    <p class="text-bold">Release date</p>
    <p><?= h($game->release_date) ?></p>

    <!-- rating -->
    <p class="text-bold">Rating</p>
    <p><?= h($game->rating) ?></p>

    <!-- case_img -->
    <p class="text-bold">Case image</p>
    <img src="<?= URL_ROOT ?>uploads/product_images/product_<?= h($game->id) ?>/<?= h($game->case_img) ?>" alt="<?= h($game->title) ?> case">

    <!-- cover_img -->
    <p class="text-bold">Cover image</p>
    <img src="<?= URL_ROOT ?>uploads/product_images/product_<?= h($game->id) ?>/<?= h($game->cover_img) ?>" alt="<?= h($game->title) ?> cover">

    <!-- trailer -->
    <p class="text-bold">Trailer</p>
    <p><a href="<?= h($game->trailer) ?>" class="text-link"><?= h($game->trailer) ?></a></p>
</div>
