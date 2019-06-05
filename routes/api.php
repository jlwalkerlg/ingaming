<?php

// GAMES
// ==================================================
Router::post('/games', 'Games@search');


// CART
// ==================================================
Router::post('/cart', 'Cart@addProduct');

Router::put('/cart/product/{product}', 'Cart@updateProduct')->where('product', '[0-9]+')->middleware('can:crud,product');

Router::delete('/cart/product/{product}', 'Cart@deleteProduct')->where('product', '[0-9]+')->middleware('can:crud,product');


// CHECKOUT
// ==================================================
Router::post('/checkout/confirm', 'Checkout@confirm');
