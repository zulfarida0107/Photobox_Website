<?php

namespace App\Controllers\User_Booking;

use App\Models\SlotModel;
use App\Models\BoothModel;
use App\Models\PrintOptionModel;
use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\CustomerModel;
use App\Controllers\Api\BaseApiController;

/**
 * UserBookingBridgeController
 * 
 * Jembatan API antara User_Booking (port 8081) dengan data Admin (port 8080).
 * Semua endpoint di sini bersifat READ-ONLY dari sisi User_Booking,
 * kecuali createBooking dan confirmPayment yang memang perlu menulis ke database.
 */
class UserBookingBridgeController extends BaseApiController
{
    protected SlotModel         $slotModel;
    protected BoothModel        $boothModel;
    protected PrintOptionModel  $printOptionModel;
    protected BookingModel      $bookingModel;
    protected PaymentModel      $paymentModel;
    protected CustomerModel     $customerModel;

    public function __construct()
    {
        $this->slotModel        = new SlotModel();
        $this->boothModel       = new BoothModel();
        $this->printOptionModel = new PrintOptionModel();
        $this->bookingModel     = new BookingModel();
        $this->paymentModel     = new PaymentModel();
        $this->customerModel    = new CustomerModel();
    }

    // =========================================================================
    // GET /api/user/slots
    // Ambil semua slot yang masih tersedia (belum penuh), untuk ditampilkan
    // di kalender pemilihan jadwal halaman User_Booking.
    // =========================================================================
    public function getSlots()
    {
        $slots = $this->slotModel
            ->select('slots.*, booths.name AS booth_name')
            ->join('booths', 'booths.id = slots.booth_id', 'left')
            ->where('slots.slot_date >=', date('Y-m-d'))
            ->orderBy('slots.slot_date', 'ASC')
            ->orderBy('slots.start_time', 'ASC')
            ->findAll();

        // Tambahkan info apakah slot masih tersedia
        $result = array_map(function ($slot) {
            $slot['is_available'] = ($slot['booked_count'] < $slot['max_bookings']);
            $slot['remaining']    = max(0, $slot['max_bookings'] - $slot['booked_count']);
            return $slot;
        }, $slots);

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Daftar slot jadwal berhasil diambil',
            'data'    => $result,
            'total'   => count($result),
        ]);
    }

    // =========================================================================
    // GET /api/user/slots/{date}
    // Ambil slot operasional unik dari Admin (tidak bergantung tanggal spesifik).
    // Tanggal hanya dipakai untuk memfilter jam yang sudah lewat (jika hari ini).
    // =========================================================================
    public function getSlotsByDate($date = null)
    {
        if (!$date || !strtotime($date)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 400,
                'error'  => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.',
            ]);
        }

        $today     = date('Y-m-d');
        $isToday   = ($date === $today);
        $nowTime   = date('H:i:s');

        // Cek apakah tanggal ini diblokir/libur oleh Admin
        $blockedDateModel = new \App\Models\BlockedDateModel();
        if ($blockedDateModel->isBlocked($date)) {
            $blocked = $blockedDateModel->where('blocked_date', $date)->first();
            return $this->respondOk([
                'status'  => 'success',
                'message' => 'Tanggal ini tidak tersedia untuk booking.',
                'reason'  => $blocked['reason'] ?? 'Libur',
                'blocked' => true,
                'data'    => [],
                'total'   => 0,
            ]);
        }

        // Ambil jam-jam operasional unik dari slot yang ada di Admin
        // Tidak filter berdasarkan slot_date — ambil semua slot yang terdaftar
        // lalu deduplicate per (start_time, booth_id) dengan subquery agar aman di MySQL strict mode
        $db = \Config\Database::connect();
        
        // Cek apakah ada slot operasional khusus yang didaftarkan untuk tanggal ini
        $specificSlotsCount = $db->query("SELECT COUNT(*) AS total FROM slots WHERE slot_date = ?", [$date])->getRowArray();
        
        if ((int)($specificSlotsCount['total'] ?? 0) > 0) {
            // Jika ada slot khusus untuk tanggal ini, gunakan slot tersebut (berfungsi sebagai override / meliburkan hari)
            $rawSlots = $db->query("
                SELECT s.id, s.start_time, s.booth_id, b.name AS booth_name,
                       s.max_bookings, s.booked_count,
                       (s.max_bookings - s.booked_count) AS remaining
                FROM slots s
                LEFT JOIN booths b ON b.id = s.booth_id
                WHERE b.status != 'maintenance'
                  AND s.slot_date = ?
                ORDER BY s.start_time ASC
            ", [$date])->getResultArray();
        } else {
            // Jika tidak ada, gunakan slot template default (2099-12-31)
            $rawSlots = $db->query("
                SELECT s.id, s.start_time, s.booth_id, b.name AS booth_name,
                       s.max_bookings,
                       (
                           SELECT COUNT(*) 
                           FROM bookings bk 
                           WHERE bk.slot_id = s.id 
                             AND DATE(bk.created_at) = ? 
                             AND bk.status != 'cancelled'
                       ) AS booked_count,
                       (
                           s.max_bookings - (
                               SELECT COUNT(*) 
                               FROM bookings bk 
                               WHERE bk.slot_id = s.id 
                                 AND DATE(bk.created_at) = ? 
                                 AND bk.status != 'cancelled'
                           )
                       ) AS remaining
                FROM slots s
                LEFT JOIN booths b ON b.id = s.booth_id
                WHERE b.status != 'maintenance'
                  AND s.slot_date = '2099-12-31'
                ORDER BY s.start_time ASC
            ", [$date, $date])->getResultArray();
        }

        $result = [];
        foreach ($rawSlots as $slot) {
            $isPast = $isToday && ($slot['start_time'] <= $nowTime);
            $remaining = max(0, (int)$slot['remaining']);
            $isAvailable = $remaining > 0 && !$isPast;

            // Calculate end time consistent with Admin Panel
            $startHour = (int) date('H', strtotime($slot['start_time']));
            $dur = ($startHour >= 19) ? 10800 : 7200; // Malam = 3 jam, lainnya = 2 jam
            $endTime = date('H:i:s', strtotime($slot['start_time']) + $dur);

            $result[] = [
                'id'           => $slot['id'],
                'booth_id'     => $slot['booth_id'],
                'booth_name'   => $slot['booth_name'] ?? 'Booth',
                'start_time'   => $slot['start_time'],
                'end_time'     => $endTime,
                'max_bookings' => $slot['max_bookings'],
                'booked_count' => $slot['booked_count'],
                'remaining'    => $remaining,
                'is_available' => $isAvailable,
                'is_past'      => $isPast,
            ];
        }

        return $this->respondOk([
            'status'  => 'success',
            'message' => "Slot operasional untuk tanggal {$date}",
            'data'    => $result,
            'total'   => count($result),
        ]);
    }

    // =========================================================================
    // GET /api/user/booths
    // Ambil semua booth yang berstatus aktif, untuk pilihan booth User_Booking.
    // =========================================================================
    public function getBooths()
    {
        $booths = $this->boothModel
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Daftar booth aktif berhasil diambil',
            'data'    => $booths,
            'total'   => count($booths),
        ]);
    }

    // =========================================================================
    // GET /api/user/packages
    // Ambil semua paket & harga yang tersedia, untuk ditampilkan di User_Booking.
    // =========================================================================
    public function getPackages()
    {
        $packages = $this->packageModel
            ->orderBy('base_price', 'ASC')
            ->findAll();

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Daftar paket berhasil diambil',
            'data'    => $packages,
            'total'   => count($packages),
        ]);
    }

    // =========================================================================
    // GET /api/user/categories
    // Ambil semua kategori aset desain dari Admin (tabel frame_categories)
    // =========================================================================
    public function getCategories()
    {
        $categories = $this->slotModel->db
            ->table('frame_categories')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Daftar kategori frame berhasil diambil dari Admin',
            'data'    => $categories,
            'total'   => count($categories),
        ]);
    }

    // =========================================================================
    // GET /api/user/frames
    // Ambil semua frame aktif beserta nama kategori & info layout
    // =========================================================================
    public function getFrames()
    {
        $frames = $this->slotModel->db
            ->table('frames')
            ->select('frames.*, frame_categories.name as category_name, frame_layouts.name as layout_name')
            ->join('frame_categories', 'frame_categories.id = frames.category_id', 'left')
            ->join('frame_layouts', 'frame_layouts.id = frames.layout_id', 'left')
            ->where('frames.is_active', 1)
            ->orderBy('frames.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Daftar frame aktif berhasil diambil dari Admin',
            'data'    => $frames,
            'total'   => count($frames),
        ]);
    }

    // =========================================================================
    // GET /api/user/frames/{category_id}
    // Ambil frame aktif berdasarkan kategori tertentu dari Admin
    // =========================================================================
    public function getFramesByCategory($categoryId = null)
    {
        $builder = $this->slotModel->db
            ->table('frames')
            ->select('frames.*, frame_categories.name as category_name, frame_layouts.name as layout_name')
            ->join('frame_categories', 'frame_categories.id = frames.category_id', 'left')
            ->join('frame_layouts', 'frame_layouts.id = frames.layout_id', 'left')
            ->where('frames.is_active', 1);

        if ($categoryId && $categoryId !== 'all') {
            $builder->where('frames.category_id', $categoryId);
        }

        $frames = $builder->orderBy('frames.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Daftar frame berdasarkan kategori berhasil diambil',
            'data'    => $frames,
            'total'   => count($frames),
        ]);
    }

    // =========================================================================
    // GET /api/user/booking/{booking_code}
    // Ambil status dan detail booking berdasarkan kode booking.
    // Digunakan di halaman Status Booking User_Booking.
    // =========================================================================
    public function getBookingStatus($bookingCode = null)
    {
        if (!$bookingCode) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 400, 'error' => 'Kode booking tidak ditemukan.']);
        }

        $booking = $this->bookingModel
            ->select('bookings.*, customers.name AS customer_name, customers.email AS customer_email, customers.phone AS customer_phone, slots.slot_date, slots.start_time, slots.end_time, booths.name AS booth_name, CONCAT(print_options.copies, \' Cetak\') AS print_option_name, print_options.copies AS print_copies, print_options.extra_price AS print_price')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('slots',     'slots.id = bookings.slot_id', 'left')
            ->join('booths',    'booths.id = bookings.booth_id', 'left')
            ->join('print_options',  'print_options.id = bookings.print_option_id', 'left')
            ->where('bookings.booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return $this->failNotFound('Booking dengan kode ' . $bookingCode . ' tidak ditemukan.');
        }

        // Ambil info pembayaran terkait
        $payment = $this->paymentModel
            ->where('booking_id', $booking['id'])
            ->orderBy('created_at', 'DESC')
            ->first();

        $booking['payment'] = $payment ?: null;

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Detail booking berhasil diambil',
            'data'    => $booking,
        ]);
    }

    // =========================================================================
    // POST /api/user/booking/create
    // Buat booking baru dari User_Booking.
    // Body: customer_id, slot_id, booth_id, package_id, booking_date, booking_time
    // =========================================================================
    public function createBooking()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        $required = ['customer_id', 'slot_id', 'booth_id', 'print_option_id'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 422, 'error' => "Field '{$field}' wajib diisi."]);
            }
        }

        // Cek slot masih tersedia
        $slot = $this->slotModel->find($input['slot_id']);
        if (!$slot) {
            return $this->failNotFound('Slot tidak ditemukan.');
        }
        if ($slot['booked_count'] >= $slot['max_bookings']) {
            return $this->response->setStatusCode(409)->setJSON(['status' => 409, 'error' => 'Slot sudah penuh. Silakan pilih jadwal lain.']);
        }

        // Generate kode booking 6 digit unik untuk mesin Kiosk
        do {
            $bookingCode = (string) rand(100000, 999999);
        } while ($this->bookingModel->where('booking_code', $bookingCode)->first() !== null);

        // Ambil harga dari Daftar Cetak (print_options) bukan packages
        $printOptionId = $input['print_option_id'] ?? $input['package_id'] ?? null;
        $printOption   = $this->printOptionModel->find($printOptionId);
        $totalAmount   = $printOption['extra_price'] ?? 0;

        // Hitung nomor antrean berurutan per slot (1, 2, 3, dst.)
        $queueNumber = $this->bookingModel->where('slot_id', $input['slot_id'])->countAllResults() + 1;

        $bookingData = [
            'booking_code'    => $bookingCode,
            'customer_id'     => $input['customer_id'],
            'slot_id'         => $input['slot_id'],
            'booth_id'        => $input['booth_id'],
            'print_option_id' => $printOptionId,  // Data dari Daftar Cetak Admin
            'queue_number'    => $queueNumber,
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ];

        $bookingId = $this->bookingModel->insert($bookingData, true);

        if (!$bookingId) {
            return $this->response->setStatusCode(500)->setJSON(['status' => 500, 'error' => 'Gagal membuat booking. Silakan coba lagi.']);
        }

        // Update booked_count pada slot
        $this->slotModel->update($input['slot_id'], [
            'booked_count' => $slot['booked_count'] + 1,
        ]);

        return $this->respondCreated([
            'status'       => 'success',
            'message'      => 'Booking berhasil dibuat!',
            'booking_code' => $bookingCode,
            'booking_id'   => $bookingId,
            'total_amount' => $totalAmount,
        ]);
    }

    // =========================================================================
    // POST /api/user/payment/confirm
    // Konfirmasi pembayaran dari User_Booking ke Admin.
    // Body: booking_id, payment_method, amount
    // =========================================================================
    public function confirmPayment()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();

        $required = ['booking_id', 'amount'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 422, 'error' => "Field '{$field}' wajib diisi."]);
            }
        }

        $booking = $this->bookingModel->find($input['booking_id']);
        if (!$booking) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        // Cek apakah sudah ada payment sebelumnya
        $existing = $this->paymentModel
            ->where('booking_id', $input['booking_id'])
            ->first();

        $paymentRef = 'PAY-' . strtoupper(uniqid());

        if ($existing) {
            $this->paymentModel->update($existing['id'], [
                'amount'         => $input['amount'],
                'payment_method' => $input['payment_method'] ?? 'QRIS',
                'gateway_ref'    => $paymentRef,
                'status'         => 'settlement',
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
            $paymentId = $existing['id'];
        } else {
            $paymentId = 'PAY' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $this->paymentModel->insert([
                'id'             => $paymentId,
                'booking_id'     => $input['booking_id'],
                'print_option_id'=> $input['print_option_id'] ?? 1,
                'amount'         => $input['amount'],
                'payment_method' => $input['payment_method'] ?? 'QRIS',
                'gateway_ref'    => $paymentRef,
                'status'         => 'settlement',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        // Update status booking menjadi paid
        $this->bookingModel->update($input['booking_id'], [
            'status'     => 'paid',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->respondOk([
            'status'     => 'success',
            'message'    => 'Pembayaran berhasil dikonfirmasi!',
            'payment_id' => $paymentId,
            'booking_id' => $input['booking_id'],
        ]);
    }

    // =========================================================================
    // GET /api/user/payment/{payment_id}/status
    // Cek status pembayaran berdasarkan payment ID.
    // Digunakan untuk polling status setelah konfirmasi pembayaran.
    // =========================================================================
    public function getPaymentStatus($paymentId = null)
    {
        if (!$paymentId) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 400, 'error' => 'Payment ID tidak ditemukan.']);
        }

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return $this->failNotFound('Payment tidak ditemukan.');
        }

        $booking = $this->bookingModel->find($payment['booking_id']);

        return $this->respondOk([
            'status'  => 'success',
            'message' => 'Status pembayaran berhasil diambil',
            'data'    => [
                'payment_id'     => $payment['id'],
                'payment_status' => $payment['status'],
                'payment_method' => $payment['payment_method'],
                'amount'         => $payment['amount'],
                'booking_code'   => $booking['booking_code'] ?? null,
                'booking_status' => $booking['status'] ?? null,
            ],
        ]);
    }
}
