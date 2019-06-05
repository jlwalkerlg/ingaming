<?php $this->extends('default') ?>

<div class="container results mt-nav mb-5">

    <!-- Header -->
    <header class="results-header">
        <div class="flex-md justify-between align-center">
            <h1 class="h4 m-0-md">Search Games</h1>
            <!-- Sort -->
            <form action="#" method="GET" class="mb-0">
                <label for="sort" class="mr-1">Sort:</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="price_asc">Price (asc)</option>
                    <option value="price_desc">Price (desc)</option>
                    <option value="rating_asc">Rating (asc)</option>
                    <option value="rating_desc" selected>Rating (desc)</option>
                    <option value="release_asc">Release date (asc)</option>
                    <option value="release_desc">Release date (desc)</option>
                </select>
            </form>
        </div>
        <hr>
    </header>

    <!-- Filter -->
    <section class="results-filter mb-5">
        <p class="results-filter-title h4 m-0">Filter</p>
        <!-- Filter toggle button -->
        <button id="resultsFilterToggle" class="results-filter-toggle btn btn-plain">
            <span>Filter</span>
            <?php include PUBLIC_ROOT . '/img/icons/arrow-down.svg' ?>
        </button>
        <hr>
        <!-- Filter Form -->
        <form action="<?= url('games/search') ?>" method="GET" id="resultsFilterForm" class="results-filter-form">
            <!-- Platforms -->
            <div>
                <p class="h4 mt-0">Platform</p>
                <div class="mt-1">
                    <label for="platformAll" class="mr-1">All platforms:</label>
                    <input type="checkbox" name="platform[]" id="platformAll" value="all" checked>
                </div>
                <?php foreach ($platforms as $platform) : ?>
                    <div class="mt-1">
                        <label for="platform<?= h($platform->id) ?>" class="text-muted mr-1"><?= h($platform->name) ?>:</label>
                        <input type="checkbox" name="platform[]" id="platform<?= h($platform->id) ?>" value="<?= h($platform->id) ?>" checked>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
            <!-- Category -->
            <div>
                <p class="h4 mt-0">Category</p>
                <div class="mt-1">
                    <label for="catAll" class="mr-1">All categories:</label>
                    <input type="checkbox" name="category[]" id="catAll" value="all" checked>
                </div>
                <?php foreach ($categories as $category) : ?>
                    <?php $catDisplayName = ucfirst($category->name); ?>
                    <div class="mt-1">
                        <label for="cat<?= h($category->id) ?>" class="text-muted mr-1"><?= h($catDisplayName) ?>:</label>
                        <input type="checkbox" name="category[]" id="cat<?= h($category->id) ?>" value="<?= h($category->id) ?>" checked>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
            <!-- Price -->
            <div>
                <p class="h4 mt-0">Price</p>
                <div class="mt-1">
                    <label for="minPrice" class="mr-1">Min:</label>
                    <input type="number" name="min_price" step="0.01" min="0" max="999.99" id="minPrice" class="form-input form-input--money" value="0">
                </div>
                <div class="mt-1">
                    <label for="maxPrice" class="mr-1">Max:</label>
                    <input type="number" name="max_price" step="0.01" min="0" max="999.99" id="maxPrice" class="form-input form-input--money" value="60.00">
                </div>
            </div>
            <hr>
            <!-- Rating -->
            <div>
                <p class="h4 mt-0">Rating</p>
                <div class="mt-1">
                    <label for="minRating" class="mr-1">Min:</label>
                    <select name="min_rating" id="minRating" class="form-select">
                        <option value="1" selected>1</option>
                        <?php for ($i = 2; $i < 6; $i++) : ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mt-1">
                    <label for="maxRating" class="mr-1">Max:</label>
                    <select name="max_rating" id="maxRating" class="form-select">
                        <?php for ($i = 1; $i < 5; $i++) : ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                        <option value="5" selected>5</option>
                    </select>
                </div>
            </div>
            <hr>
            <!-- Release date -->
            <p class="h4 mt-0">Release date</p>
            <div class="mt-1">
                <label for="releaseSoon" class="mr-1">Coming soon</label>
                <input type="checkbox" name="release" id="releaseSoon" value="soon" checked>
            </div>
            <div class="mt-1">
                <label for="releaseNow" class="mr-1">Out now</label>
                <input type="checkbox" name="release" id="releaseNow" value="now" checked>
            </div>
            <!-- Submit -->
            <input type="submit" value="Apply" class="btn btn-block btn-primary mt-3">
        </form>
    </section>

    <!-- Results -->
    <section id="productsList" class="products-list results-list">
        <?php foreach ($games as $game) : ?>
            <a href="<?= h(url("games/$game->id")) ?>" class="product">
                <img src="<?= URL_ROOT ?>uploads/product_images/product_<?= h($game->id) ?>/<?= h($game->case_img) ?>" alt="<?= h($game->title) ?>" width="131" height="165">
                <p class="product-title my-1"><?= h($game->title) ?></p>
                <p class="product-release my-1" data-release-date=<?= h($game->release_date) ?>><?= $game->isOut() ? 'Out now' : h($game->release_date) ?></p>
                <div class="stars">
                    <?php for ($j = 0; $j < $game->rating; $j++) : ?>
                        <img src="<?= url('img/icons/star.svg') ?>" alt="star" class="star">
                    <?php endfor; ?>
                </div>
                <p class="product-price my-1">£<?= h($game->price) ?></p>
            </a>
        <?php endforeach; ?>
    </section>

    <!-- Pagination -->
    <div class="results-pagination">
        <?= $paginationLinks ?>
    </div>

</div>
