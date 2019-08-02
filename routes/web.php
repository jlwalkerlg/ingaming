<?php

// HOME
// ==================================================
Router::get('/', 'Home@index');


// USERS
// ==================================================
Router::get('/login', 'Users@showLogin');

Router::post('/login', 'Users@login');

Router::get('/register', 'Users@showRegister');

Router::post('/register', 'Users@register');

Router::get('/logout', 'Users@logout');


// GAMES
// ==================================================
Router::get('/games', 'Games@index');

Router::get('/games/page/{page}', 'Games@index')->where('page', '[0-9]+');

Router::get('/games/{id}', 'Games@show')->where('id', '[0-9]+');


// CART
// ==================================================
Router::get('/cart', 'Cart@show')->middleware('auth');

Router::post('/cart', 'Cart@addProduct');


// CHECKOUT
// ==================================================
Router::get('/checkout', 'Checkout@index');

Router::post('/checkout', 'Checkout@confirm');

Router::get('/checkout/success/{transaction}', 'Checkout@success')->middleware('can:view,transaction');


// ADMIN
// ==================================================
Router::get('/admin', 'Admin@index');

Router::get('/admin/games', 'Admin@showGames');

Router::get('/admin/games/page/{page}', 'Admin@showGames');

Router::get('/admin/games/new', 'Admin@newGame');

Router::post('/admin/games', 'Admin@createGame');

Router::get('/admin/games/{id}', 'Admin@viewGame')->where('id', '[0-9]+');

Router::delete('/admin/games/{id}', 'Admin@deleteGame')->where('id', '[0-9]+');

Router::get('/admin/games/{game}/edit', 'Admin@editGame')->where('game', '[0-9]+');

Router::put('/admin/games/{game}', 'Admin@updateGame')->where('game', '[0-9]+');

Router::get('/admin/featured', 'Admin@editFeatured');

Router::post('/admin/featured', 'Admin@updateFeatured');
