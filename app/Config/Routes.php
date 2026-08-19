<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// HOME / DEFAULT ROUTE (Customer Landing Page & Online Booking)
// ============================================================
$routes->get('/', 'User_Booking\LandingPageController::index');

// Handle global OPTIONS requests for CORS preflight
$routes->options('(:any)', function() {});

// ============================================================
// API ROUTES
// ============================================================
$routes->group('api', static function ($routes) {
    $routes->post('customer/booking/verify', 'Api\\CustomerController::verifyBooking');
    $routes->post('customer/booking/create-walkin', 'Api\\CustomerController::createWalkin');
    $routes->get('customer/print-options', 'Api\\CatalogController::listPrintOptions');
    $routes->get('customer/frames', 'Api\\CatalogController::listFrames');
    $routes->get('customer/frame', 'Api\\CatalogController::listFrames');
    $routes->get('booths/(:segment)/session-check', 'Api\\BoothApiController::sessionCheck/$1');

    $routes->post('payment/qr', 'Api\\PaymentController::createQr');
    $routes->get('payment', 'Api\\PaymentController::statusByQuery');
    $routes->get('payment/(:segment)/status', 'Api\\PaymentController::status/$1');
    $routes->post('payment/midtrans/callback', 'Api\\PaymentController::midtransCallback');

    $routes->post('session/start', 'Api\\SessionController::start');
    $routes->post('session/(:segment)/email', 'Api\\SessionController::submitEmail/$1');
    $routes->post('session/(:segment)/complete', 'Api\\SessionController::complete/$1');

    $routes->post('photo/session/(:segment)/upload', 'Api\\PhotoController::upload/$1');
<<<<<<< HEAD
=======
	$routes->get('frame/(:any)', 'Api\\FrameController::show/$1');
>>>>>>> 809e25cbdcf9b46ae7d84d91ea5cae63ea09a3e1
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

// ============================================================
// ADMIN AUTH ROUTES
// ============================================================
$routes->get('admin/login', 'Admin\AuthController::login');
$routes->post('admin/login', 'Admin\AuthController::attemptLogin');
$routes->get('admin/logout', 'Admin\AuthController::logout');

// ============================================================
// CUSTOMER ROUTES
// ============================================================
$routes->group('customer', static function ($routes) {
    $routes->get('/', 'Customer\CustomerController::index');
    $routes->get('booking', 'Customer\CustomerController::bookingForm');
    $routes->post('create-booking', 'Customer\CustomerController::createBooking');
    $routes->get('verify', 'Customer\CustomerController::verifyPage');
    $routes->post('check-verification', 'Customer\CustomerController::checkVerification');
    $routes->get('choose-frame', 'Customer\CustomerController::chooseFrame');
    $routes->post('save-frame', 'Customer\CustomerController::saveFrameChoice');
    $routes->get('bookings/(:segment)', 'Customer\CustomerController::showBooking/$1');
    $routes->get('my-bookings/(:segment)', 'Customer\CustomerController::myBookings/$1');

    // Camera
    $routes->get('camera', 'Customer\CameraController::index');
    $routes->post('camera/upload', 'Customer\CameraController::upload');
    $routes->post('camera/save-photo', 'Customer\CameraController::savePhoto');

    // Payment
    $routes->get('payment', 'Customer\PaymentController::index');
    $routes->post('payment/process', 'Customer\PaymentController::processPayment');
    $routes->get('payment/status', 'Customer\PaymentController::checkStatus');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {

	// ---- Tab 1: Dashboard ----
	$routes->get('/',         'Admin\AdminDashboardController::index');
	$routes->get('dashboard', 'Admin\AdminDashboardController::index');

	// Redirect alias (salah ketik URL)
	$routes->get('booking',  function() { return redirect()->to(base_url('admin/bookings')); });
	$routes->get('customer', function() { return redirect()->to(base_url('admin/customers')); });
	$routes->get('payment',  function() { return redirect()->to(base_url('admin/payments')); });

	// ---- Tab 2: Manajemen Pelanggan ----
	$routes->get('customers',                    'Admin\CustomerController::index');
	$routes->get('customers/create',             'Admin\CustomerController::create');
	$routes->post('customers',                   'Admin\CustomerController::store');
	$routes->get('customers/(:segment)',         'Admin\CustomerController::show/$1');
	$routes->get('customers/(:segment)/edit',    'Admin\CustomerController::edit/$1');
	$routes->post('customers/(:segment)/update', 'Admin\CustomerController::update/$1');
	$routes->post('customers/(:segment)/delete', 'Admin\CustomerController::destroy/$1');

	// ---- Tab 3: Manajemen Reservasi & Slot ----
	$routes->get('bookings',                     'Admin\BookingAndSlotController::index');
	$routes->get('bookings/export',              'Admin\BookingAndSlotController::export');
	$routes->get('bookings/(:segment)',          'Admin\BookingAndSlotController::show/$1');
	$routes->get('bookings/(:segment)/edit',     'Admin\BookingAndSlotController::edit/$1');
	$routes->post('bookings/(:segment)/update',  'Admin\BookingAndSlotController::update/$1');
	$routes->post('bookings/(:segment)/cancel',  'Admin\BookingAndSlotController::cancel/$1');
	$routes->get('slots',                        'Admin\BookingAndSlotController::slots');
	$routes->get('slots/create',                 'Admin\BookingAndSlotController::createSlot');
	$routes->post('slots',                       'Admin\BookingAndSlotController::storeSlot');

	// ---- Tab 4: Manajemen Pembayaran ----
	$routes->get('payments',                     'Admin\PaymentController::index');
	$routes->post('payments/verify-bulk',        'Admin\PaymentController::verifyBulk');
	$routes->post('payments/(:segment)/verify',  'Admin\PaymentController::verify/$1');
	$routes->get('payments/(:segment)/invoice',  'Admin\PaymentController::invoice/$1');
	$routes->get('payments/export',              'Admin\PaymentController::export');

	// ---- Tab 5: Paket & Cetak ----
	$routes->get('packages',                         'Admin\PackageAndPrintOptionController::index');
	$routes->get('packages/create',                  'Admin\PackageAndPrintOptionController::createPackage');
	$routes->post('packages',                        'Admin\PackageAndPrintOptionController::storePackage');
	$routes->get('packages/export',                  'Admin\PackageAndPrintOptionController::exportPackages');
	$routes->get('packages/(:segment)/edit',         'Admin\PackageAndPrintOptionController::editPackage/$1');
	$routes->post('packages/(:segment)/update',      'Admin\PackageAndPrintOptionController::updatePackage/$1');
	$routes->post('packages/(:segment)/delete',      'Admin\PackageAndPrintOptionController::deletePackage/$1');
	$routes->get('print-options',                    'Admin\PackageAndPrintOptionController::printOptions');
	$routes->get('print-options/create',             'Admin\PackageAndPrintOptionController::createPrintOption');
	$routes->post('print-options',                   'Admin\PackageAndPrintOptionController::storePrintOption');
	$routes->get('print-options/(:segment)/edit',    'Admin\PackageAndPrintOptionController::editPrintOption/$1');
	$routes->post('print-options/(:segment)/update', 'Admin\PackageAndPrintOptionController::updatePrintOption/$1');
	$routes->post('print-options/(:segment)/delete', 'Admin\PackageAndPrintOptionController::deletePrintOption/$1');

	// ---- Tab 6: Aset Desain (Frame & Filter) ----
	$routes->get('frames',                       'Admin\FrameAndFilterController::index');
	$routes->get('frames/create',                'Admin\FrameAndFilterController::createFrame');
	$routes->post('frames',                      'Admin\FrameAndFilterController::storeFrame');
	$routes->get('frames/(:segment)/edit',       'Admin\FrameAndFilterController::editFrame/$1');
	$routes->post('frames/(:segment)/update',    'Admin\FrameAndFilterController::updateFrame/$1');
	$routes->post('frames/(:segment)/toggle',    'Admin\FrameAndFilterController::toggleFrame/$1');
	$routes->post('frames/(:segment)/delete',    'Admin\FrameAndFilterController::deleteFrame/$1');
	$routes->get('filters/create',               'Admin\FrameAndFilterController::createFilter');
	$routes->post('filters',                     'Admin\FrameAndFilterController::storeFilter');
	$routes->get('filters/(:segment)/edit',      'Admin\FrameAndFilterController::editFilter/$1');
	$routes->post('filters/(:segment)/update',   'Admin\FrameAndFilterController::updateFilter/$1');
	$routes->post('filters/(:segment)/toggle',   'Admin\FrameAndFilterController::toggleFilter/$1');
	$routes->post('filters/(:segment)/delete',   'Admin\FrameAndFilterController::deleteFilter/$1');

	// ---- Kategori Frame ----
	$routes->post('categories',                  'Admin\FrameAndFilterController::storeCategory');
	$routes->post('categories/(:segment)/update', 'Admin\FrameAndFilterController::updateCategory/$1');
	$routes->post('categories/(:segment)/delete', 'Admin\FrameAndFilterController::deleteCategory/$1');

	// ---- Layout Frame ----
	$routes->post('layouts',                     'Admin\FrameAndFilterController::storeLayout');
	$routes->post('layouts/(:segment)/update',   'Admin\FrameAndFilterController::updateLayout/$1');
	$routes->post('layouts/(:segment)/delete',   'Admin\FrameAndFilterController::deleteLayout/$1');

	// ---- Tab 7: Mesin Booth ----
	$routes->get('booths',                      'Admin\BoothController::index');
	$routes->get('booths/create',               'Admin\BoothController::create');
	$routes->post('booths',                     'Admin\BoothController::store');
	$routes->get('booths/export',               'Admin\BoothController::export');
	$routes->get('booths/(:segment)',           'Admin\BoothController::show/$1');
	$routes->get('booths/(:segment)/edit',      'Admin\BoothController::edit/$1');
	$routes->post('booths/(:segment)/update',   'Admin\BoothController::update/$1');
	$routes->post('booths/(:segment)/maintenance', 'Admin\BoothController::maintenance/$1');
	$routes->post('booths/(:segment)/activate', 'Admin\BoothController::activate/$1');
	$routes->post('booths/(:segment)/delete',   'Admin\BoothController::delete/$1');
	$routes->post('booths/(:segment)/reset-session', 'Admin\BoothController::resetActiveSession/$1');

	// ---- Tab 8: Sesi & Galeri Foto ----
	$routes->get('sessions',                        'Admin\SessionAndPhotoController::index');
	$routes->get('sessions/export',                 'Admin\SessionAndPhotoController::export');
	$routes->get('sessions/resend-all',             'Admin\SessionAndPhotoController::resendAll');
	$routes->get('sessions/(:segment)',             'Admin\SessionAndPhotoController::show/$1');
	$routes->post('sessions/(:segment)/resend',     'Admin\SessionAndPhotoController::resend/$1');
	$routes->post('sessions/(:segment)/delete',     'Admin\SessionAndPhotoController::deleteSession/$1');
	$routes->post('photos/(:segment)/delete',       'Admin\SessionAndPhotoController::deletePhoto/$1');

	// ---- Tab 9: Pengaturan Akses (Admin) ----
	$routes->get('settings',                      'Admin\AdminAccessController::index');
	$routes->get('settings/create',               'Admin\AdminAccessController::create');
	$routes->post('settings',                     'Admin\AdminAccessController::store');
	$routes->get('settings/(:segment)/edit',      'Admin\AdminAccessController::edit/$1');
	$routes->post('settings/(:segment)/update',   'Admin\AdminAccessController::update/$1');
	$routes->post('settings/(:segment)/delete',   'Admin\AdminAccessController::delete/$1');
});

// ============================================================
// USER BOOKING ROUTES (Customer Online Booking & Landing Page)
// ============================================================
<<<<<<< HEAD
$routes->get('index', 'User_Booking\LandingPageController::index');
=======
$routes->get('landing', 'User_Booking\LandingPageController::index');
>>>>>>> 809e25cbdcf9b46ae7d84d91ea5cae63ea09a3e1

$routes->get('register', 'User_Booking\RegisterController::index');
$routes->post('register/store', 'User_Booking\RegisterController::store');
$routes->post('register/from-landing', 'User_Booking\RegisterController::fromLanding');
$routes->post('register/check-email', 'User_Booking\RegisterController::checkEmail');

// Routes untuk Login
$routes->get('login', 'User_Booking\LoginController::index');
$routes->post('login/auth', 'User_Booking\LoginController::auth');
$routes->get('logout', 'User_Booking\LoginController::logout');

$routes->get('schedule', 'User_Booking\ScheduleController::index');
$routes->post('schedule', 'User_Booking\ScheduleController::store');
$routes->get('schedule/getSlots', 'User_Booking\ScheduleController::getSlots');
$routes->post('schedule/book', 'User_Booking\ScheduleController::book');
$routes->post('schedule/submit', 'User_Booking\ScheduleController::submitSchedule');

<<<<<<< HEAD
// Frame Routes
$routes->get('frame', 'User_Booking\FrameController::index');
$routes->post('frame/store', 'User_Booking\FrameController::store');
=======
$routes->get('booth', 'User_Booking\BoothController::index');
$routes->post('booth/store', 'User_Booking\BoothController::store');
>>>>>>> 809e25cbdcf9b46ae7d84d91ea5cae63ea09a3e1

$routes->get('user-payment', 'User_Booking\PaymentMethodController::index');
$routes->post('user-payment/store', 'User_Booking\PaymentMethodController::store');
$routes->get('user-payment/detail/(:any)', 'User_Booking\PaymentMethodController::detail/$1');
$routes->post('user-payment/confirm', 'User_Booking\PaymentMethodController::confirm');
$routes->get('user-payment/success/(:any)', 'User_Booking\PaymentMethodController::success/$1');
<<<<<<< HEAD
$routes->post('user-payment/confirm', 'User_Booking\PaymentMethodController::confirm');
=======
>>>>>>> 809e25cbdcf9b46ae7d84d91ea5cae63ea09a3e1

$routes->get('StatusBooking', 'User_Booking\StatusBookingController::index');
$routes->post('StatusBooking/store', 'User_Booking\StatusBookingController::store');

$routes->get('barcode', 'User_Booking\BarcodeController::index');
$routes->get('barcode/generate/(:any)', 'User_Booking\BarcodeController::generate/$1');
$routes->get('barcode/scan', 'User_Booking\BarcodeController::scan');
$routes->post('barcode/verify', 'User_Booking\BarcodeController::verify');

<<<<<<< HEAD
// Routes untuk Help & Language
$routes->get('help', 'User_Booking\HelpController::index');
$routes->post('help/send', 'User_Booking\HelpController::send');
$routes->get('language/change/(:any)', 'User_Booking\LanguageController::change/$1');

=======
>>>>>>> 809e25cbdcf9b46ae7d84d91ea5cae63ea09a3e1
