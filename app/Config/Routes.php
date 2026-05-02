<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/register', 'Home::register');

// user
$routes->get('/dashboard', 'Home::dashboard');

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
});
