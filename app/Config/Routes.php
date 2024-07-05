<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Users::index');

$routes->group('auth', ['namespace' => 'App\Controllers'], static function ($routes) {
    $routes->get('users/logout', 'Users::logOut');
    $routes->post('users/login', 'Users::login');
});

$routes->group('/', ['namespace' => 'App\Controllers'], static function ($routes) {

    $routes->get('productos/new', 'Productos::new', ['filter' => 'auth:admin']);
    $routes->post('productos', 'Productos::create', ['filter' => 'auth:admin']);
    $routes->get('productos', 'Productos::index',   ['filter' => 'auth:admin,user']);
    $routes->get('productos/(:segment)/edit', 'Productos::edit/$1', ['filter' => 'auth:admin']);
    $routes->put('productos/(:segment)', 'Productos::update/$1',    ['filter' => 'auth:admin']);
    $routes->delete('productos/(:segment)', 'Productos::delete/$1', ['filter' => 'auth:admin']);

    $routes->post('productos/search', 'Productos::search',          ['filter' => 'auth:admin,user']);
    $routes->post('productos/page', 'Productos::searchByPage',      ['filter' => 'auth:admin,user']);
});
