<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ============================================================
// CUSTOMER ROUTES
// ============================================================
$routes->group('customer', static function ($routes) {
    // Customer main
    $routes->get('/', 'Customer\\CustomerController::index');
    
    // Camera routes
    $routes->get('camera', 'Customer\\CameraController::index');
    $routes->post('camera/upload', 'Customer\\CameraController::upload');
    $routes->post('camera/save-photo', 'Customer\\CameraController::savePhoto');
    
    // Booking routes
    $routes->get('booking', 'Customer\\CustomerController::bookingForm');
    $routes->post('bookings', 'Customer\\CustomerController::createBooking');
    $routes->get('bookings/(:segment)', 'Customer\\CustomerController::showBooking/$1');
	$routes->get('customers/(:segment)/bookings', 'Customer\\CustomerController::myBookings/$1');
    
    // Verification routes
    $routes->get('verify', 'Customer\\CustomerController::verifyPage');
    $routes->post('verify/check', 'Customer\\CustomerController::checkVerification');
    
    // Frame routes - 4 cards version
    $routes->get('choose-frame', 'Customer\\ChooseFrameController::index');
    $routes->post('save-frame', 'Customer\\ChooseFrameController::save');
    
    // Frame routes - 6 cards version
    $routes->get('all-frame', 'Customer\\FrameController::allFrame');
    
    // Payment routes
    $routes->get('payment', 'Customer\\PaymentController::index');
    $routes->post('payment/process', 'Customer\\PaymentController::processPayment');
    $routes->get('payment/status', 'Customer\\PaymentController::checkStatus');
    
    // Customer bookings history
    $routes->get('customers/(:segment)/bookings', 'Customer\\CustomerController::myBookings/$1');
});

// ============================================================
// API ROUTES
// ============================================================
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

// ============================================================
// PREVIEW ROUTES
// ============================================================
$routes->get('preview/frame', function() {
    $dummyData = [
        'booking' => [
            'id' => 'DEMO001',
            'booking_code' => 'DEMO001',
            'verification_code' => '123456'
        ]
    ];
    return view('customer/all_frame', $dummyData);
});

$routes->get('preview/verify', function() {
    $dummyData = [
        'booking' => [
            'id' => 'DEMO001',
            'booking_code' => 'DEMO001',
            'verification_code' => '123456'
        ]
    ];
    return view('customer/verify_code', $dummyData);
});