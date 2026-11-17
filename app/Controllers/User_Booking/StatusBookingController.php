<?php

/**
 * Berkas: app/Controllers/User_Booking/StatusBookingController.php
 * Proyek: CuitBox Studio - Photobox Website & Management System
 * Author: Zulfarida (Lead Backend & Web Developer)
 * 
 * Standar Kompetensi Kerja Nasional Indonesia (SKKNI):
 * - Unit SKKNI: J.620100.005.02 (Mengimplementasikan User Interface)
 * - Unit SKKNI: J.620100.015.01 (Menyusun Fungsi, File atau Sumber Daya Pemrograman dalam Organisasi yang Rapi)
 * - Unit SKKNI: J.620100.016.01 (Menulis Kode dengan Prinsip Sesuai Guidelines dan Best Practices)
 * - Unit SKKNI: J.620100.017.02 (Mengimplementasikan Pemrograman Terstruktur - Transaksi Basis Data)
 * - Unit SKKNI: J.620100.019.02 (Menggunakan Library atau Komponen Pre-Existing - Bayar.gg Payment Gateway)
 * 
 * Deskripsi:
 * Mengendalikan pemantauan status reservasi dan konfirmasi pembayaran pelanggan.
 * Mengimplementasikan mekanisme live fallback inquiry ke API Payment Gateway (Bayar.gg),
 * manajemen transaksi atomik basis data (Database Transaction Begin, Commit, Rollback),
 * penggabungan entitas relasional (Booking, Customer, Booth, Slot, PrintOption),
 * serta pemetaan status tiket visual (badge warna dan ikon status).
 */

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\SlotModel;
use App\Models\BoothModel;
use App\Models\PrintOptionModel;
use App\Models\PaymentModel;
use App\Models\FrameModel;
use App\Libraries\BayarGGService;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Class StatusBookingController
 *
 * Mengendalikan halaman pemantauan status pesanan tiket dan penyelarasan status transaksi pelanggan.
 *
 * @package App\Controllers\User_Booking
 */
class StatusBookingController extends BaseController
{
    /** @var BookingModel */
    protected $bookingModel;

    /** @var CustomerModel */
    protected $customerModel;

    /** @var SlotModel */
    protected $slotModel;

    /** @var BoothModel */
    protected $boothModel;

    /** @var PrintOptionModel */
    protected $printOptionModel;

    /** @var PaymentModel */
    protected $paymentModel;

    /** @var FrameModel */
    protected $frameModel;

    /** @var \CodeIgniter\Session\Session */
    protected $session;

    /**
     * Inisialisasi controller dan instansiasi model-model relasi
     */
    public function __construct()
    {
        $this->bookingModel     = new BookingModel();
        $this->customerModel    = new CustomerModel();
        $this->slotModel        = new SlotModel();
        $this->boothModel       = new BoothModel();
        $this->printOptionModel = new PrintOptionModel();
        $this->paymentModel     = new PaymentModel();
        $this->frameModel       = new FrameModel();
        $this->session          = \Config\Services::session();
    }

    /**
     * Menampilkan halaman status pemesanan dan melakukan sinkronisasi status pembayaran live
     *
     * Alur pemrosesan:
     * 1. Mendeteksi ID pembayaran atau ID booking dari query GET atau session
     * 2. Live Fallback Inquiry: Jika transaksi berstatus 'pending', sistem memanggil API BayarGGService
     * 3. Eksekusi transaksi basis data (Atomic Transaction): memperbarui status di tabel 'payments' dan 'bookings'
     * 4. Membaca detail lengkap pemesanan (pelanggan, booth, jadwal slot, opsi cetak)
     * 5. Merender tampilan antarmuka status pemesanan beserta lencana (badge) status
     *
     * @return string Render view Status_Booking
     */
    public function index()
    {
        $data = [
            'title'   => 'Status Booking - CuitBox Studio',
            'booking' => null,
            'payment' => null,
        ];

        // 1. Ekstraksi identifier transaksi dari query parameter atau session aktif
        $paymentId = $this->request->getGet('payment_id') ?? $this->session->get('konfirmasi_pembayaran_id');
        $bookingId = $this->request->getGet('booking_id') ?? $this->session->get('booking_id');

        $payment = null;
        if ($paymentId) {
            $payment = $this->paymentModel->find($paymentId);
            if ($payment && empty($bookingId)) {
                $bookingId = $payment['booking_id'];
            }
        } elseif ($bookingId) {
            $payment = $this->paymentModel
                ->where('booking_id', $bookingId)
                ->orderBy('created_at', 'DESC')
                ->first();
            if ($payment) {
                $paymentId = $payment['id'];
            }
        }

        // 2. Mekanisme Sinkronisasi Live: Cek status ke Payment Gateway jika status lokal masih 'pending'
        if ($payment && $payment['status'] === 'pending' && !empty($payment['gateway_ref'])) {
            try {
                $bayargg        = new BayarGGService();
                $statusResponse = $bayargg->checkPayment($payment['gateway_ref']);

                $invoiceData   = $statusResponse['data'] ?? $statusResponse;
                $bayarggStatus = $invoiceData['status'] ?? '';
                $mappedStatus  = $bayargg->mapStatus($bayarggStatus);

                // Mulai transaksi atomik basis data (Database Transaction - SKKNI Unit 04)
                $db = \Config\Database::connect();
                $db->transBegin();

                if ($mappedStatus === 'paid') {
                    $this->paymentModel->update($payment['id'], [
                        'status'         => 'paid',
                        'payment_method' => 'QRIS Bayar.gg',
                        'updated_at'     => date('Y-m-d H:i:s'),
                    ]);

                    if (!empty($payment['booking_id'])) {
                        $this->bookingModel->update($payment['booking_id'], [
                            'status'     => 'paid',
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                } elseif ($mappedStatus === 'failed') {
                    $this->paymentModel->update($payment['id'], [
                        'status'     => 'failed',
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    if (!empty($payment['booking_id'])) {
                        $this->bookingModel->update($payment['booking_id'], [
                            'status'     => 'cancelled',
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }

                // Evaluasi status transaksi atomik (Commit jika berhasil, Rollback jika terjadi galat)
                if ($db->transStatus() === false) {
                    $db->transRollback();
                } else {
                    $db->transCommit();
                }

                // Muat ulang data pembayaran teranyar setelah pembaruan
                $payment = $this->paymentModel->find($payment['id']);
            } catch (\Throwable $e) {
                // Catat log notifikasi jika layanan gateway offline atau dalam antrean pemrosesan
                log_message('notice', '[BayarGG Live Inquiry Notice] ' . $e->getMessage());
            }
        }

        // 3. Muat detail relasi pemesanan jika ID booking ditemukan
        if ($bookingId) {
            $this->session->set('booking_id', $bookingId);
            $bookingDetail = $this->getBookingDetail($bookingId);
            $data['booking'] = $bookingDetail;
        }

        $data['payment'] = $payment;

        return view('User_Booking/Status_Booking', $data);
    }

    /**
     * Memproses pengalihan ke tiket digital berbasis QR Code / Barcode
     *
     * @return RedirectResponse Redirect ke halaman barcode tiket
     */
    public function store()
    {
        $bookingId = $this->request->getPost('booking_id') ?? $this->session->get('booking_id');
        if ($bookingId) {
            $this->session->set('booking_id', $bookingId);
        }
        return redirect()->to('barcode');
    }

    /**
     * Mengambil dan memetakan struktur lengkap detail pemesanan sesi foto
     *
     * @param string|int $bookingId ID pemesanan yang dicari
     * @return array|null Array asosiatif detail booking lengkap atau null jika tidak ditemukan
     */
    private function getBookingDetail($bookingId)
    {
        // 1. Ambil data master booking
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            return null;
        }

        // 2. Ambil entitas relasional terkait
        $customer    = $this->customerModel->find($booking['customer_id'] ?? 0);
        $booth       = $this->boothModel->find($booking['booth_id'] ?? 0);
        $printOption = $this->printOptionModel->find($booking['print_option_id'] ?? 0);

        // 3. Format tanggal dan jam slot operasional
        $slotDate = null;
        $slotTime = null;
        if (!empty($booking['slot_id'])) {
            $slot = $this->slotModel->find($booking['slot_id']);
            if ($slot) {
                $slotDate = (!empty($slot['slot_date']) && $slot['slot_date'] !== '2099-12-31')
                    ? date('d F Y', strtotime($slot['slot_date']))
                    : date('d F Y');
                $startFmt = date('H:i', strtotime($slot['start_time']));
                $endHour  = (int) date('H', strtotime($slot['start_time']));
                $dur      = ($endHour >= 19) ? 10800 : 7200;
                $endFmt   = date('H:i', strtotime($slot['start_time']) + $dur);
                $slotTime = $startFmt . ' – ' . $endFmt;
            }
        }

        // 4. Pemetaan visual status pemesanan (Warna lencana dan icon)
        $statusMap = [
            'paid'      => ['label' => 'Terbayar & Terkonfirmasi', 'badge' => 'bg-emerald-100 text-emerald-800 border border-emerald-300', 'icon' => 'check_circle'],
            'completed' => ['label' => 'Sesi Selesai',             'badge' => 'bg-purple-100 text-purple-800 border border-purple-300',   'icon' => 'task_alt'],
            'pending'   => ['label' => 'Menunggu Pembayaran',      'badge' => 'bg-amber-100 text-amber-800 border border-amber-300',     'icon' => 'hourglass_empty'],
            'cancelled' => ['label' => 'Dibatalkan',               'badge' => 'bg-red-100 text-red-800 border border-red-300',           'icon' => 'cancel'],
        ];

        $statusKey = $booking['status'] ?? 'pending';
        $meta = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'badge' => 'bg-gray-100 text-gray-800', 'icon' => 'info'];

        // 5. Susun struktur array siap pakai untuk antarmuka pengguna
        return [
            'id'           => $booking['id'],
            'booking_code' => $booking['booking_code'] ?? 'N/A',
            'queue_number' => $booking['queue_number'] ?? null,
            'status'       => $statusKey,
            'status_label' => $meta['label'],
            'status_badge' => $meta['badge'],
            'status_icon'  => $meta['icon'],
            'booking_date' => $slotDate ?? (isset($booking['booking_date']) ? date('d F Y', strtotime($booking['booking_date'])) : date('d F Y')),
            'booking_time' => $slotTime ?? (isset($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) : date('H:i') . ' WIB'),
            'duration'     => 1,
            'customer'     => $customer ?: [
                'name'  => $this->session->get('customer_name') ?? 'Pelanggan CuitBox',
                'email' => $this->session->get('customer_email') ?? 'N/A',
                'phone' => $this->session->get('customer_phone') ?? 'N/A',
            ],
            'booth'        => $booth ?: ['name' => 'Booth Studio A', 'location' => 'Main Studio'],
            'print_option' => $printOption ? ['name' => ($printOption['copies'] ?? 2) . ' Lembar Cetak', 'price' => $printOption['extra_price']] : null,
            'created_at'   => $booking['created_at'] ?? date('Y-m-d H:i:s'),
        ];
    }
}
