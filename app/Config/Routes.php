<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Home::register');

// user
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/buku/detail/(:num)', 'Home::detail/$1');

// Routes yang butuh login (user biasa)
$routes->group('', ['filter' => 'login'], static function ($routes) {
    // Profile
    $routes->get('/profile', 'UserController::profile');
    $routes->post('/profile/update', 'UserController::updateProfile');

    // Cart
    $routes->get('/cart', 'CartController::index');
    $routes->post('/cart/add', 'CartController::add');
    $routes->post('/cart/update', 'CartController::update');
    $routes->get('/cart/delete/(:num)', 'CartController::delete/$1');

    // Checkout
    $routes->get('/checkout', 'CheckoutController::index');
    $routes->post('/checkout/process', 'CheckoutController::process');

    // Customer Orders
    $routes->get('/orders', 'OrderController::index');
    $routes->get('/orders/detail/(:num)', 'OrderController::detail/$1');
});

// admin
$routes->group('', ['filter' => 'role:admin'], static function ($routes) {
    // Admin Dashboard
    $routes->get('/admin/dashboard',     'Admin\Dashboard::index');

    // Buku
    $routes->get('/buku',                'Admin\Buku::index');
    $routes->get('/buku/create',         'Admin\Buku::create');
    $routes->post('/buku/store',         'Admin\Buku::store');
    $routes->get('/buku/edit/(:num)',    'Admin\Buku::edit/$1');
    $routes->post('/buku/update/(:num)', 'Admin\Buku::update/$1');
    $routes->get('/buku/delete/(:num)',  'Admin\Buku::delete/$1');

    // Kelola User
    $routes->get('/user',                'Admin\User::index');
    $routes->get('/user/create',         'Admin\User::create');
    $routes->post('/user/store',         'Admin\User::store');
    $routes->get('/user/edit/(:num)',    'Admin\User::edit/$1');
    $routes->post('/user/update/(:num)', 'Admin\User::update/$1');
    $routes->get('/user/delete/(:num)',  'Admin\User::delete/$1');

    // Kelola Pesanan Customer (Monitoring)
    $routes->get('/admin/orders', 'Admin\OrderController::index');
    $routes->get('/admin/orders/detail/(:num)', 'Admin\OrderController::detail/$1');
    $routes->post('/admin/orders/update-status/(:num)', 'Admin\OrderController::updateStatus/$1');
});
