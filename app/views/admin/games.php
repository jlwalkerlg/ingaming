<?php $this->extends('default') ?>

<div class="full-screen">
    <div class="container mt-nav hide-overflow">
        <h1>Admin Dashboard</h1>

        <h2 class="h4">Games</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($games as $game) : ?>
                    <tr>
                        <td><?= h($game->id) ?></td>
                        <td><?= h($game->title) ?></td>
                        <td>
                            <a href="<?= url('/admin/games/' . $game->id) ?>" class="text-link">View</a>
                            <a href="<?= url('/admin/games/' . $game->id . '/edit') ?>" class="text-link">Edit</a>
                            <form action="<?= url('/admin/games/' . $game->id) ?>" method="POST" class="delete-form d-inblock" data-game-id="<?= h($game->id) ?>">
                                <?= CSRF::input() ?>
                                <?= spoofVerb('delete') ?>
                                <input class="btn btn-plain text-link d-inline" type="submit" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $paginator->numberedLinks() ?>
    </div>
</div>
