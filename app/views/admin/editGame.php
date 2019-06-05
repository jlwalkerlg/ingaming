<?php $this->extends('default') ?>

<?php $this->block('body') ?>

<div class="container hide-overflow mt-nav">
    <!-- Back -->
    <p class="mt-5"><a href="<?= url('/admin/games') ?>" class="text-link">Back to games</a></p>

    <h1>Edit game</h1>

    <form action="<?= url('/admin/games/' . $game->id) ?>" method="POST" enctype="multipart/form-data" class="mb-5">
        <?= CSRF::input() ?>
        <?= spoofVerb('put') ?>

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-input form-input-block" placeholder="Title" value="<?= h(old('title') ?? $game->title) ?>">
            <?= formError($errors['title'] ?? '') ?>
        </div>

        <!-- Blurb -->
        <div class="mb-4">
            <label for="blurb" class="form-label">Blurb</label>
            <textarea id="blurb" name="blurb" id="blurb" rows="3" class="form-input form-input-area form-input-block" placeholder="Blurb"><?= purify(old('blurb') ?? $game->blurb) ?></textarea>
            <?= formError($errors['blurb'] ?? '') ?>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" id="description" rows="3" class="form-input form-input-area form-input-block" placeholder="Description"><?= purify(old('description') ?? $game->description) ?></textarea>
            <?= formError($errors['description'] ?? '') ?>
        </div>

        <div class="flex-sm">
            <!-- Platform -->
            <div class="flex-1 mb-4">
                <label for="platformId" class="form-label">Platform</label>
                <select name="platform_id" id="platformId" class="form-select form-input-block">
                    <option value="" disabled selected>Select platform...</option>
                    <?php foreach ($platforms as $platform) : ?>
                        <option value="<?= h($platform->id) ?>" <?= $platform->id == (old('platform_id') ?? $game->platform_id) ? 'selected' : '' ?>><?= h($platform->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= formError($errors['platform_id'] ?? '') ?>
            </div>

            <!-- Price -->
            <div class="flex-1 mb-4 ml-4-sm">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-input form-input-block form-input--money" step="0.01" min="0" max="999.99" placeholder="Price" value="<?= h(old('price') ?? $game->price) ?>">
                <?= formError($errors['price'] ?? '') ?>
            </div>

            <!-- Release date -->
            <div class="flex-1 mb-4 ml-4-sm">
                <label for="releaseDate" class="form-label">Release Date</label>
                <input type="date" name="release_date" id="releaseDate" class="form-input form-input-block" placeholder="Release Date" value="<?= h(old('release_date') ?? $game->release_date) ?>">
                <?= formError($errors['release_date'] ?? '') ?>
            </div>

            <!-- Rating -->
            <div class="flex-1 mb-4 ml-4-sm">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" id="rating" class="form-select form-input-block">
                    <option value="" disabled selected>Select rating...</option>
                    <?php for ($i = 1; $i < 6; $i++) : ?>
                        <option value="<?= $i ?>" <?= $i == (old('rating') ?? $game->rating) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <?= formError($errors['rating'] ?? '') ?>
            </div>
        </div>

        <div class="flex-sm">
            <!-- Case img -->
            <div class="flex-1 mb-4">
                <p class="form-label">Case Image</p>
                <label for="caseImg" class="form-input form-input-block form-label-file">Upload image...</label>
                <input type="file" name="case_img" id="caseImg" class="form-input-file">
                <?= formError($errors['case_img'] ?? '') ?>
            </div>

            <!-- Cover img -->
            <div class="flex-1 ml-4-sm mb-4">
                <p class="form-label">Cover Image</p>
                <label for="coverImg" class="form-input form-input-block form-label-file">Upload image...</label>
                <input type="file" name="cover_img" id="coverImg" class="form-input-file">
                <?= formError($errors['cover_img'] ?? '') ?>
            </div>
        </div>

        <!-- Trailer -->
        <div class="mb-4">
            <label for="trailer" class="form-label">Trailer embed link</label>
            <input type="text" name="trailer" id="trailer" class="form-input form-input-block" placeholder="Trailer embed link" value="<?= h(old('trailer') ?? $game->trailer) ?>">
            <?= formError($errors['trailer'] ?? '') ?>
        </div>

        <input type="submit" value="Submit" class="btn btn-primary btn-block">

    </form>
</div>

<?php $this->endblock() ?>



<?php $this->block('foot') ?>

<script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=<?= TINYMCE_KEY ?>"></script>

<script>
    const options = {
        plugins: 'code',
        toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent code',
    }

    tinymce.init({
        selector: '#blurb',
        ...options
    });

    tinymce.init({
        selector: '#description',
        ...options
    });
</script>

<?php $this->endblock() ?>
