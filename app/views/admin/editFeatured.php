<?php $this->extends('default') ?>

<div class="full-screen">
    <div class="container mt-nav hide-overflow">
        <h1>Admin Dashboard</h1>

        <h2 class="h4">Set Featured</h2>

        <form action="<?= url('/admin/featured') ?>" method="post">

            <?= CSRF::input() ?>

            <div class="d-inblock mr-4">
                <select class="form-select" name="game_id" id="gameId">
                    <?php foreach ($games as $game) : ?>
                        <option value="<?= h(old('game_id') ?? $game->id) ?>" <?= $game->featured ? 'selected' : '' ?>><?= h($game->title) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= formError($errors['game_id'] ?? '') ?>
            </div>

            <input type="submit" value="Submit" class="btn btn-primary v-top">

        </form>
    </div>
</div>
