<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// HOME / DEFAULT ROUTE
// Port 8080 -> Admin Panel (app/Views/Admin)
// Port 8081 -> User Booking (app/Views/User_Booking)
// ============================================================
$serverPort = $_SERVER['SERVER_PORT'] ?? '8080';

if ($serverPort == '8080') {
    $routes->get('/', function () {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return redirect()->to(base_url('admin/login'));
    });
} else {
    $routes->get('/', 'User_Booking\LandingPageController::index');
}


// Handle global OPTIONS requests for CORS preflight
$routes->options('(:any)', function() {});

// ============================================================
// API ROUTES
// ============================================================
$routes->group('api', static function ($routes) {

    // ---- Verifikasi & Registrasi Tiket Walk-in (Kiosk Desktop) ----
    $routes->post('customer/booking/verify', 'Api\CustomerController::verifyBooking');
    $routes->post('customer/booking/create-walkin', 'Api\CustomerController::createWalkin');

    // ---- Katalog Opsi Cetak & Aset Frame Desain (Kiosk Desktop) ----
    $routes->get('customer/print-options', 'Api\CatalogController::listPrintOptions');
    $routes->get('customer/frames', 'Api\CatalogController::listFrames');
    $routes->get('customer/frame', 'Api\CatalogController::listFrames');
    $routes->get('frame/(:any)', 'Api\FrameController::show/$1');

    // ---- Monitoring & Pengecekan Sesi Mesin Booth ----
    $routes->get('booths/(:segment)/session-check', 'Api\BoothApiController::sessionCheck/$1');

    // ---- Transaksi & Gateway Pembayaran QRIS (Midtrans) ----
    $routes->post('payment/qr', 'Api\PaymentController::createQr');
    $routes->get('payment', 'Api\PaymentController::statusByQuery');
    $routes->get('payment/(:segment)/status', 'Api\PaymentController::status/$1');
    $routes->post('payment/midtrans/callback', 'Api\PaymentController::midtransCallback');

    // ---- Manajemen Alur Sesi Pemotretan (Start, Email, Complete) ----
    $routes->post('session/start', 'Api\SessionController::start');
    $routes->post('session/(:segment)/email', 'Api\SessionController::submitEmail/$1');
    $routes->post('session/(:segment)/complete', 'Api\SessionController::complete/$1');

    // ---- Integrasi Google OAuth & Cloud Drive ----
    $routes->get('google/auth', 'GoogleAuthController::auth');
    $routes->get('google/callback', 'GoogleAuthController::callback');

    // ---- Pengambilan Gambar DSLR, Live View & Upload Foto ----
    $routes->post('session/(:segment)/upload', 'Api\SessionController::upload/$1');
    $routes->get('session/settings', 'Api\SessionController::getSettings');
    $routes->post('session/(:segment)/upload-live', 'Api\SessionController::uploadLive/$1');
    $routes->post('session/(:segment)/capture-dslr', 'Api\SessionController::captureDslr/$1');
    $routes->get('session/photo/temp/(:any)', 'Api\SessionController::getTempPhoto/$1');
    $routes->post('session/liveview/start', 'Api\SessionController::startLiveView');
    $routes->post('session/liveview/stop', 'Api\SessionController::stopLiveView');
    $routes->post('photo/session/(:segment)/upload', 'Api\PhotoController::upload/$1');

    // ---- Bridge Integrasi Slot Jadwal & Booth (User_Booking <-> Admin) ----
    $routes->get('user/slots', 'User_Booking\UserBookingBridgeController::getSlots');
    $routes->get('user/slots/(:segment)', 'User_Booking\UserBookingBridgeController::getSlotsByDate/$1');
    $routes->get('user/booths', 'User_Booking\UserBookingBridgeController::getBooths');

    // ---- Bridge Integrasi Paket & Kategori Desain (User_Booking <-> Admin) ----
    $routes->get('user/packages', 'User_Booking\UserBookingBridgeController::getPackages');
    $routes->get('user/categories', 'User_Booking\UserBookingBridgeController::getCategories');
    $routes->get('user/frames', 'User_Booking\UserBookingBridgeController::getFrames');
    $routes->get('user/frames/(:segment)', 'User_Booking\UserBookingBridgeController::getFramesByCategory/$1');

    // ---- Bridge Reservasi Booking & Transaksi Pembayaran (User_Booking <-> Admin) ----
    $routes->get('user/booking/(:segment)', 'User_Booking\UserBookingBridgeController::getBookingStatus/$1');
    $routes->post('user/booking/create', 'User_Booking\UserBookingBridgeController::createBooking');
    $routes->post('user/payment/confirm', 'User_Booking\UserBookingBridgeController::confirmPayment');
    $routes->get('user/payment/(:segment)/status', 'User_Booking\UserBookingBridgeController::getPaymentStatus/$1');

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
	$routes->get('slots/(:segment)/edit',        'Admin\BookingAndSlotController::editSlot/$1');
	$routes->post('slots/(:segment)/update',     'Admin\BookingAndSlotController::updateSlot/$1');
	$routes->post('slots/(:segment)/delete',     'Admin\BookingAndSlotController::deleteSlot/$1');

	// ---- Tanggal Libur (Blocked Dates) ----
	$routes->post('blocked-dates',              'Admin\BookingAndSlotController::storeBlockedDate');
	$routes->post('blocked-dates/(:num)/delete','Admin\BookingAndSlotController::deleteBlockedDate/$1');

	// ---- Tab 4: Manajemen Pembayaran ----
	$routes->get('payments',                     'Admin\PaymentController::index');
	$routes->post('payments/verify-bulk',        'Admin\PaymentController::verifyBulk');
	$routes->post('payments/(:segment)/verify',  'Admin\PaymentController::verify/$1');
	$routes->get('payments/(:segment)/invoice',  'Admin\PaymentController::invoice/$1');
	$routes->get('payments/export',              'Admin\PaymentController::export');

	// ---- Tab 7: Mesin Booth & Lokasi Cabang ----
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

	// ---- Manajemen Lokasi Cabang (Sub-fitur Tab 7) ----
	$routes->post('locations',                     'Admin\BoothController::storeLocation');
	$routes->get('locations/(:segment)/edit',      'Admin\BoothController::editLocation/$1');
	$routes->post('locations/(:segment)/update',   'Admin\BoothController::updateLocation/$1');
	$routes->post('locations/(:segment)/delete',   'Admin\BoothController::deleteLocation/$1');
	$routes->post('locations/(:segment)/toggle',   'Admin\BoothController::toggleLocation/$1');

	// ---- Tab 8: Sesi & Galeri Foto ----
	$routes->get('sessions',                        'Admin\SessionAndPhotoController::index');
	$routes->get('sessions/export',                 'Admin\SessionAndPhotoController::export');
	$routes->get('sessions/resend-all',             'Admin\SessionAndPhotoController::resendAll');
	$routes->get('sessions/(:segment)',             'Admin\SessionAndPhotoController::show/$1');
	$routes->post('sessions/(:segment)/resend',     'Admin\SessionAndPhotoController::resend/$1');
	$routes->post('sessions/(:segment)/delete',     'Admin\SessionAndPhotoController::deleteSession/$1');
	$routes->post('photos/(:segment)/delete',       'Admin\SessionAndPhotoController::deletePhoto/$1');

	// ============================================================
	// RUTE KATALOG (Admin dan Superadmin)
	// ============================================================
	$routes->group('', ['filter' => 'adminCatalogAuth'], static function ($routes) {
		// ---- Tab 5: Opsi Cetak & Harga ----
		$routes->get('print-options',                    'Admin\PackageAndPrintOptionController::index');
		$routes->get('print-options/export',             'Admin\PackageAndPrintOptionController::exportPrintOptions');
		$routes->get('print-options/create',             'Admin\PackageAndPrintOptionController::createPrintOption');
		$routes->post('print-options',                   'Admin\PackageAndPrintOptionController::storePrintOption');
		$routes->get('print-options/(:segment)/edit',    'Admin\PackageAndPrintOptionController::editPrintOption/$1');
		$routes->post('print-options/(:segment)/update', 'Admin\PackageAndPrintOptionController::updatePrintOption/$1');
		$routes->post('print-options/(:segment)/delete', 'Admin\PackageAndPrintOptionController::deletePrintOption/$1');

		// ---- Tab 6: Aset Desain (Frame & Filter) ----
		$routes->get('frames',                       'Admin\FrameAndFilterController::index');
		$routes->get('frames/create',                'Admin\FrameAndFilterController::createFrame');
		$routes->post('frames/sync',                 'Admin\FrameAndFilterController::syncAssets');
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
	});

	// RUTE PENGELOLAAN AKUN (Admin dan Superadmin)
	$routes->group('', ['filter' => 'adminCatalogAuth'], static function ($routes) {
		// ---- Tab 9: Pengaturan Akses ----
		$routes->get('settings',                      'Admin\AdminAccessController::index');
		$routes->get('settings/create',               'Admin\AdminAccessController::create');
		$routes->post('settings',                     'Admin\AdminAccessController::store');
		$routes->get('settings/(:segment)/edit',      'Admin\AdminAccessController::edit/$1');
		$routes->post('settings/(:segment)/update',   'Admin\AdminAccessController::update/$1');
		$routes->post('settings/(:segment)/delete',   'Admin\AdminAccessController::delete/$1');
	});
});

// ============================================================
// USER BOOKING ROUTES (Customer Online Booking & Landing Page)
// ============================================================

// ---- Alur 1: Landing Page & Beranda ----
$routes->get('landing', 'User_Booking\LandingPageController::index');

// ---- Alur 2: Registrasi Pelanggan Baru ----
$routes->get('register', 'User_Booking\RegisterController::index');
$routes->post('register/store', 'User_Booking\RegisterController::store');
$routes->post('register/from-landing', 'User_Booking\RegisterController::fromLanding');
$routes->post('register/check-email', 'User_Booking\RegisterController::checkEmail');

// ---- Alur 3: Autentikasi & Login Pelanggan ----
$routes->get('login', 'User_Booking\LoginController::index');
$routes->post('login/auth', 'User_Booking\LoginController::auth');
$routes->get('logout', 'User_Booking\LoginController::logout');

// ---- Alur 4: Lupa Password ----
$routes->get('forgot-password', 'User_Booking\LoginController::forgotPassword');
$routes->post('forgot-password/process', 'User_Booking\LoginController::processForgotPassword');

// ---- Alur 5: Pemilihan Mesin Booth ----
$routes->get('booth', 'User_Booking\BoothController::index');
$routes->post('booth/store', 'User_Booking\BoothController::store');

// ---- Alur 6: Pemilihan Tanggal & Sesi Jadwal (Schedule) ----
$routes->get('schedule', 'User_Booking\ScheduleController::index');
$routes->post('schedule', 'User_Booking\ScheduleController::store');
$routes->get('schedule/getSlots', 'User_Booking\ScheduleController::getSlots');
$routes->post('schedule/book', 'User_Booking\ScheduleController::book');
$routes->post('schedule/submit', 'User_Booking\ScheduleController::submitSchedule');

// ---- Alur 7: Pemilihan Paket & Opsi Cetak Foto ----
$routes->get('print-option', 'User_Booking\PrintOptionController::index');
$routes->post('print-option/store', 'User_Booking\PrintOptionController::store');

// ---- Alur 8: Pembayaran Online (QRIS & Konfirmasi) ----
$routes->get('user-payment', 'User_Booking\PaymentMethodController::index');
$routes->post('user-payment/store', 'User_Booking\PaymentMethodController::store');
$routes->get('user-payment/detail/(:any)', 'User_Booking\PaymentMethodController::detail/$1');
$routes->post('user-payment/confirm', 'User_Booking\PaymentMethodController::confirm');
$routes->get('user-payment/success/(:any)', 'User_Booking\PaymentMethodController::success/$1');
$routes->get('booking/summary', 'User_Booking\PaymentMethodController::index');

// ---- Alur 9: Tiket Booking, Barcode / QR Code & Verifikasi ----
$routes->get('barcode', 'User_Booking\BarcodeController::index');
$routes->get('barcode/generate/(:any)', 'User_Booking\BarcodeController::generate/$1');
$routes->get('barcode/scan', 'User_Booking\BarcodeController::scan');
$routes->post('barcode/verify', 'User_Booking\BarcodeController::verify');
$routes->post('frame/send-email', 'User_Booking\BarcodeController::sendEmail');

// ---- Alur 10: Status & Riwayat Booking Pelanggan ----
$routes->get('StatusBooking', 'User_Booking\StatusBookingController::index');
$routes->post('StatusBooking/store', 'User_Booking\StatusBookingController::store');
$routes->get('statusbooking', 'User_Booking\StatusBookingController::index');  // alias lowercase

// ---- Alur 11: Pusat Bantuan & Layanan Pelanggan (Help) ----
$routes->get('help', 'User_Booking\HelpController::index');
$routes->post('help/send', 'User_Booking\HelpController::send');

