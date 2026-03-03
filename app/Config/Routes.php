<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

//user//
$routes->group('user', function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('add', 'UserController::add');
    $routes->post('store', 'UserController::store');
    $routes->get('login', 'UserController::login');
    $routes->post('authenticate', 'UserController::authenticate');
    $routes->get('logout', 'UserController::logout');
    $routes->get('profile/(:num)', 'UserController::profile/$1');
    $routes->post('show/(:num)', 'UserController::show/$1');
    $routes->put('update/(:num)', 'UserController::update/$1');
    $routes->delete('delete/(:num)', 'UserController::delete/$1');
});

$routes->setAutoRoute(true);

//Games//
$routes->group('games', function($routes) {
    $routes->get('/', 'GameController::index');
    $routes->get('create', 'GameController::create');
    $routes->post('store', 'GameController::store');
    $routes->put('update/(:num)', 'GameController::update/$1');
    $routes->delete('delete/(:num)', 'GameController::delete/$1');
});

//transactions//
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('transactions', 'TransactionController::index');
    $routes->get('transactions/confirm', 'TransactionController::confirm');
    $routes->get('transactions/payment-instructions/(:num)', 'TransactionController::paymentInstructions/$1');  // <- PASTIKAN INI
    $routes->post('transactions/upload-proof/(:num)', 'TransactionController::uploadProof/$1');
    $routes->get('transactions/detail/(:num)', 'TransactionController::detail/$1');
    $routes->post('transactions/store', 'TransactionController::store');

    $routes->put('transactions/update/(:num)', 'TransactionController::update/$1');
    $routes->delete('transactions/delete/(:num)', 'TransactionController::delete/$1');
});

// Payment Method routes (public)
$routes->get('payment-methods', 'PaymentMethodController::index');
$routes->get('payment-methods/topup/(:num)', 'PaymentMethodController::topup/$1');
$routes->post('payment-methods/process', 'PaymentMethodController::process'); // Proses ke session
$routes->put('payment-methods/update/(:num)', 'PaymentMethodController::update/$1');
$routes->delete('payment-methods/delete/(:num)', 'PaymentMethodController::delete/$1');

// Bank Accounts
$routes->get('bank-accounts', 'BankAccountController::index');
$routes->post('bank-accounts/store', 'BankAccountController::store');
$routes->put('bank-accounts/update/(:num)', 'BankAccountController::update/$1');
$routes->delete('bank-accounts/delete/(:num)', 'BankAccountController::delete/$1');