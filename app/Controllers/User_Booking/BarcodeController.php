<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\BoothModel;
use App\Models\PrintOptionModel;

class BarcodeController extends BaseController
{
    protected $bookingModel;
    protected $customerModel;
    protected $boothModel;
    protected $printOptionModel;
    protected $session;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->boothModel = new BoothModel();
        $this->printOptionModel = new PrintOptionModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data = [
            'title' => 'Barcode Booking - CuitBox Studio',
            'booking' => null,
            'error' => null,
            'qr_code' => null,
        ];

        if ($this->session->has('booking_id')) {
            $bookingId = $this->session->get('booking_id');
            $booking = $this->getBookingDetail($bookingId);
            if ($booking) {
                $data['booking'] = $booking;
                $data['qr_code'] = $this->generateQRCode($booking['booking_code']);
            }
        }

        return view('user_booking/barcode', $data);
    }

    public function generate($bookingCode = null)
    {
        if (!$bookingCode) {
            return redirect()->to('StatusBooking')->with('error', '❌ Kode booking tidak ditemukan');
        }

        $booking = $this->bookingModel
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return redirect()->to('StatusBooking')->with('error', '❌ Booking tidak ditemukan');
        }

        $this->session->set('booking_id', $booking['id']);

        return redirect()->to('barcode')->with('success', '✅ Berhasil menampilkan kode booking');
    }

    // HAPUS METHOD generatePost() - Tidak perlu

    public function scan()
    {
        $data = [
            'title' => 'Scan Barcode - CuitBox Studio',
        ];

        return view('user_booking/scan_barcode', $data);
    }

    public function verify()
    {
        $bookingCode = $this->request->getPost('booking_code');

        if (!$bookingCode) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode booking tidak ditemukan'
            ]);
        }

        $booking = $this->bookingModel
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Booking tidak valid'
            ]);
        }

        if ($booking['status'] === 'cancelled') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Booking sudah dibatalkan'
            ]);
        }

        if ($booking['status'] === 'completed') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Booking sudah selesai'
            ]);
        }

        if ($booking['status'] === 'pending') {
            $this->bookingModel->update($booking['id'], [
                'status' => 'confirmed'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Booking valid!',
            'data' => [
                'booking_code' => $booking['booking_code'],
                'status' => $booking['status'],
                'customer_id' => $booking['customer_id']
            ]
        ]);
    }

    private function getBookingDetail($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return null;
        }

        $customer = $this->customerModel->find($booking['customer_id'] ?? 0);
        $booth = $this->boothModel->find($booking['booth_id'] ?? 0);
        $printOption = $this->printOptionModel->find($booking['print_option_id'] ?? 0);

        // Fetch slot details if slot_id is set
        $slotDate = null;
        $slotTime = null;
        if (!empty($booking['slot_id'])) {
            $slotModel = new \App\Models\SlotModel();
            $slot = $slotModel->find($booking['slot_id']);
            if ($slot) {
                $slotDate = (!empty($slot['slot_date']) && $slot['slot_date'] !== '2099-12-31')
                    ? date('d F Y', strtotime($slot['slot_date'])) 
                    : date('d F Y');
                $startFmt = date('H:i', strtotime($slot['start_time']));
                $endHour  = (int) date('H', strtotime($slot['start_time']));
                $dur      = ($endHour >= 19) ? 10800 : 7200; // Malam = 3 jam, lainnya = 2 jam
                $endFmt   = date('H:i', strtotime($slot['start_time']) + $dur);
                $slotTime = $startFmt . ' – ' . $endFmt;
            }
        }

        // Fetch frame name
        $frameName = session('selected_frame_name');
        if (!$frameName && !empty($booking['frame_id'])) {
            $frameModel = new \App\Models\FrameModel();
            $frame = $frameModel->find($booking['frame_id']);
            $frameName = $frame['name'] ?? null;
        }
        if (!$frameName) {
            $frameName = 'Retro Vibes';
        }

        $customerPassword = session('customer_password') ?? 'Password123!';

        $statusMap = [
            'pending'   => ['label' => 'Menunggu Pembayaran', 'color' => 'yellow'],
            'paid'      => ['label' => 'Terbayar', 'color' => 'green'],
            'confirmed' => ['label' => 'Dikonfirmasi', 'color' => 'blue'],
            'completed' => ['label' => 'Selesai', 'color' => 'purple'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red']
        ];

        $statusKey = $booking['status'] ?? 'pending';
        $status = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'color' => 'gray'];

        return [
            'id' => $booking['id'] ?? 0,
            'booking_code' => $booking['booking_code'] ?? 'N/A',
            'queue_number' => $booking['queue_number'] ?? null,
            'status' => $booking['status'] ?? 'pending',
            'status_label' => $status['label'],
            'status_color' => $status['color'],
            'booking_date' => $slotDate ?? (isset($booking['booking_date']) ? date('d F Y', strtotime($booking['booking_date'])) : date('d F Y')),
            'booking_time' => $slotTime ?? (isset($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) : '12:00 – 14:00'),
            'frame_name' => $frameName,
            'customer' => [
                'name'  => $customer['name'] ?? session('customer_name') ?? 'Pelanggan CuitBox', 
                'email' => $customer['email'] ?? session('customer_email') ?? 'N/A', 
                'phone' => $customer['phone'] ?? session('customer_phone') ?? 'N/A'
            ],
            'customer_password' => $customerPassword,
            'booth' => $booth ?: ['name' => 'Booth Studio A', 'location' => 'Main Studio'],
            'print_option' => $printOption ? ['name' => $printOption['copies'] . ' Cetak', 'duration' => '0', 'price' => $printOption['extra_price']] : ['name' => 'N/A', 'duration' => '0', 'price' => 0],
            'created_at' => $booking['created_at'] ?? date('Y-m-d H:i:s'),
        ];
    }

    private function generateQRCode($bookingCode)
    {
        $url = site_url('barcode/verify') . '?code=' . $bookingCode;
        
        return [
            'code' => $bookingCode,
            'url' => $url,
            'image' => 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url)
        ];
    }

    public function sendEmail()
    {
        $bookingId = $this->session->get('booking_id');
        if (!$bookingId) {
            return redirect()->to('barcode')->with('error', '❌ Booking ID tidak ditemukan di session.');
        }

        $booking = $this->getBookingDetail($bookingId);
        if (!$booking) {
            return redirect()->to('barcode')->with('error', '❌ Booking tidak ditemukan.');
        }

        $customerEmail = $booking['customer']['email'] ?? null;
        if (empty($customerEmail) || $customerEmail === 'N/A') {
            return redirect()->to('barcode')->with('error', '❌ Email customer tidak terdaftar.');
        }

        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode(site_url('barcode/verify') . '?code=' . $booking['booking_code']);

        $emailConfig = config('Email');
        $emailService = \Config\Services::email();
        $emailService->initialize($emailConfig);
        
        $emailService->setFrom('cuitbox.studio@gmail.com', 'CuitBox Studio');
        $emailService->setCRLF("\r\n");
        $emailService->setNewline("\r\n");
        $emailService->setMailType('html');
        $emailService->setTo($customerEmail);
        $emailService->setSubject('Tiket & Detail Booking CuitBox Studio - ' . $booking['booking_code']);

        $htmlMessage = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: "Plus Jakarta Sans", Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; }
                .ticket-container { max-width: 550px; margin: 0 auto; background: #ffffff; border-radius: 20px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
                .brand-title { text-align: center; font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
                .brand-subtitle { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 20px; }
                .user-info-box { background: #f1f5f9; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px; }
                .user-info-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #cbd5e1; font-size: 13px; }
                .user-info-row:last-child { border-bottom: none; }
                .user-info-label { color: #64748b; font-weight: 600; }
                .user-info-value { color: #0f172a; font-weight: 700; }
                .card-green { background-color: #D9F99D; border-radius: 20px; padding: 24px; text-align: center; margin-bottom: 16px; }
                .code-label { font-size: 11px; font-weight: 800; color: #365314; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 6px; }
                .code-value { font-size: 32px; font-weight: 900; color: #1e293b; margin: 0 0 16px 0; letter-spacing: 1px; }
                .qr-box { background: #ffffff; display: inline-block; padding: 16px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                .note-pill { background: rgba(54, 83, 20, 0.15); color: #365314; padding: 8px 16px; border-radius: 999px; font-size: 11px; font-weight: 600; margin-top: 16px; display: inline-block; }
                .footer-text { text-align: center; font-size: 11px; color: #94a3b8; margin-top: 24px; }
            </style>
        </head>
        <body>
            <div class="ticket-container">
                <div class="brand-title">CuitBox Studio</div>
                <div class="brand-subtitle">Tiket Resmi & Konfirmasi Pemesanan</div>

                <!-- Detail Pemesan & Pemesanan (Tabel) -->
                <div class="user-info-box">
                    <table style="width:100%; border-collapse:collapse; font-size:13px;">
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Atas Nama Pemesanan:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">' . esc($booking['customer']['name']) . '</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Email Terdaftar:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">' . esc($customerEmail) . '</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Password Akun:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">' . esc($booking['customer_password']) . '</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Tema Frame:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">' . esc($booking['frame_name']) . '</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Waktu Sesi:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">' . esc($booking['booking_date']) . ' • ' . esc($booking['booking_time']) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Nomor Antrean:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">#' . esc($booking['queue_number']) . '</td>
                        </tr>
                    </table>
                </div>

                <!-- Kartu Hijau Kode Booking & QR Code -->
                <div class="card-green">
                    <div class="code-label">KODE BOOKING ANDA</div>
                    <div class="code-value">' . esc($booking['booking_code']) . '</div>
                    <div class="qr-box">
                        <img src="' . $qrCodeUrl . '" alt="QR Code Booking" width="180" height="180" style="display:block;">
                    </div>
                    <br>
                    <div class="note-pill">ℹ️ Scan atau masukkan kode ini di mesin photobooth saat tiba</div>
                </div>

                <div class="footer-text">© 2026 CuitBox Studio. All rights reserved.</div>
            </div>
        </body>
        </html>
        ';

        $emailService->setMessage($htmlMessage);
        
        if ($emailService->send(false)) {
            $debugInfo = "Email berhasil dikirim ke " . $customerEmail . "\n" . $emailService->printDebugger(['headers', 'subject']);
            log_message('error', $debugInfo); // Force log as error
            file_put_contents(WRITEPATH . 'logs/email_debug.txt', date('Y-m-d H:i:s') . " - SUCCESS:\n" . $debugInfo . "\n", FILE_APPEND);
            return redirect()->to('barcode')->with('success', 'Pesanan berhasil, data akan dikirim ke email user');
        } else {
            $error = $emailService->printDebugger(['headers']);
            log_message('error', 'Gagal mengirim email ke ' . $customerEmail . ': ' . $error);
            file_put_contents(WRITEPATH . 'logs/email_debug.txt', date('Y-m-d H:i:s') . " - FAILED:\n" . $error . "\n", FILE_APPEND);
            return redirect()->to('barcode')->with('error', 'Gagal mengirim email: Silakan hubungi admin atau coba lagi nanti.');
        }
    }
}