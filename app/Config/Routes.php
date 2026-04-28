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

$routes->group('api', static function ($routes) {
	$routes->post('customer/booking/verify', 'Api\\CustomerController::verifyBooking');
	$routes->get('customer/print-options', 'Api\\CatalogController::listPrintOptions');
	$routes->get('customer/frames', 'Api\\CatalogController::listFrames');
	$routes->get('customer/frame', 'Api\\CatalogController::listFrames');

	$routes->post('payment/qr', 'Api\\PaymentController::createQr');
	$routes->get('payment', 'Api\\PaymentController::statusByQuery');
	$routes->get('payment/(:segment)/status', 'Api\\PaymentController::status/$1');
	$routes->post('payment/midtrans/callback', 'Api\\PaymentController::midtransCallback');

	$routes->post('session/start', 'Api\\SessionController::start');
	$routes->post('session/(:segment)/email', 'Api\\SessionController::submitEmail/$1');
	$routes->post('session/(:segment)/complete', 'Api\\SessionController::complete/$1');

	$routes->post('photo/session/(:segment)/upload', 'Api\\PhotoController::upload/$1');
});
