<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// SET AUTO ROUTE (HARUS DI AWAL)
// ============================================================
$routes->setAutoRoute(false);

// ============================================================
// LANDING PAGE
// ============================================================
$routes->get('/', 'Landing::index');

// ============================================================
// USER AUTH ROUTES (Login & Register Only)
// ============================================================
$routes->get('register', 'UserController::register');
$routes->post('register', 'UserController::doRegister');

$routes->get('test', 'Test::index');

