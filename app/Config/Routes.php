<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('customer', static function ($routes) {
	$routes->get('/', 'Customer\\CustomerController::index');
	$routes->post('bookings', 'Customer\\CustomerController::createBooking');
	$routes->get('bookings/(:segment)', 'Customer\\CustomerController::showBooking/$1');
	$routes->get('customers/(:segment)/bookings', 'Customer\\CustomerController::myBookings/$1');
});
