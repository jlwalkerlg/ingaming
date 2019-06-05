<?php $this->extends('default') ?>

<div class="full-screen">
    <div class="container mt-nav hide-overflow">
        <h1>Admin Dashboard</h1>

        <h2 class="h4">Actions</h2>
        <ul>
            <li class="my-1"><a href="<?= url('admin/games') ?>">Browse products</a></li>
            <li class="my-1"><a href="<?= url('admin/games/new') ?>">Add product</a></li>
            <li class="my-1"><a href="<?= url('admin/featured') ?>">Set featured</a></li>
        </ul>
    </div>
</div>
