<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\FrameModel;

class PaymentMethodController extends BaseController
{
    protected $paymentModel;
    protected $bookingModel;
    protected $customerModel;
    protected $frameModel;
    protected $session;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->frameModel = new FrameModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data = [
            'title' => 'Payment Method - CuitBox Studio',
            'booking' => null,
            'payment' => null,
            'frame' => null,
            'error' => null,
            'success' => null,
        ];

        // Ambil frame_id dari URL atau session
        $frameId = $this->request->getGet('frame_id') ?? $this->session->get('selected_frame');
        $frame = null;
        if ($frameId) {
            $this->session->set('selected_frame', $frameId);
            $frame = $this->frameModel->find($frameId);
            $data['frame'] = $frame;
        }

        // Ambil booth_id dari session untuk informasi lokasi
        $boothId = $this->session->get('booth_id');
        $booth = null;
        if ($boothId) {
            $boothModel = new \App\Models\BoothModel();
            $booth = $boothModel->find($boothId);
        }
        $data['booth'] = $booth;

        // Hitung total tagihan dari session
        $printOptionPrice = $this->session->get('selected_print_option_price') ?? 25000;
        $framePrice = $frame['price'] ?? $this->session->get('selected_frame_price') ?? 0;
        $totalCalculatedAmount = $printOptionPrice + $framePrice;

        // Cek booking_id dari berbagai sumber
        $bookingId = null;
        
        if ($this->request->getGet('booking_id')) {
            $bookingId = $this->request->getGet('booking_id');
            $this->session->set('booking_id', $bookingId);
        } elseif ($this->session->has('booking_id')) {
            $bookingId = $this->session->get('booking_id');
        }

        if ($bookingId) {
            $booking = $this->getBookingDetail($bookingId);
            if ($booking) {
                $booking['total_amount'] = $totalCalculatedAmount;
                $data['booking'] = $booking;
                
                $payment = $this->paymentModel
                    ->where('booking_id', $bookingId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if ($payment) {
                    $data['payment'] = $payment;
                }
            } else {
                $data['booking'] = $this->createDummyBooking($totalCalculatedAmount);
            }
        } else {
            $data['booking'] = $this->createDummyBooking($totalCalculatedAmount);
        }

        if ($this->session->getFlashdata('error')) {
            $data['error'] = $this->session->getFlashdata('error');
        }
        if ($this->session->getFlashdata('success')) {
            $data['success'] = $this->session->getFlashdata('success');
        }

        return view('User_Booking/payment_method', $data);
    }

    public function store()
    {
        // Ambil data dari POST
        $paymentId = $this->request->getPost('payment_id');
        $bookingId = $this->request->getPost('booking_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $amount = $this->request->getPost('amount');
        $status = $this->request->getPost('status');

        // Validasi
        if (empty($paymentId) || empty($amount)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data pembayaran tidak lengkap. Silahkan coba lagi.');
        }

        // Jika booking_id tidak ada, ambil dari session
        if (empty($bookingId)) {
            $bookingId = $this->session->get('booking_id');
        }

        // Cek apakah booking ada di database
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            // BOOKING BELUM ADA (ini Dummy Booking dari web flow, simpan ke DB).
            
            // Ambil customer dari session atau fallback ke yang pertama di DB
            $customerModel = new \App\Models\CustomerModel();
            $customer = $customerModel->first();
            $customerId = $this->session->get('customer_id') ?? ($customer ? $customer['id'] : null);
            
            // Ambil slot valid dari DB
            $slotModel = new \App\Models\SlotModel();
            $slot = $slotModel->first();
            $slotId = $this->session->get('slot_id') ?? ($slot ? $slot['id'] : null);
            
            // Ambil booth valid dari DB
            $boothModel = new \App\Models\BoothModel();
            $booth = $boothModel->first();
            $boothId = $this->session->get('booth_id') ?? ($booth ? $booth['id'] : null);
            
            // Ambil print_option yang dipilih user dari session (dari Daftar Cetak Admin)
            $printOptionId = $this->session->get('selected_print_option_id');
            if (empty($printOptionId)) {
                // Fallback: ambil print option pertama dari database
                $printOptionModel = new \App\Models\PrintOptionModel();
                $firstOption = $printOptionModel->first();
                $printOptionId = $firstOption ? $firstOption['id'] : null;
            }
            
            $newBookingId = 'BKG' . date('YmdHis') . rand(10, 99);
            do {
                $bookingCode = (string) rand(100000, 999999);
            } while ($this->bookingModel->where('booking_code', $bookingCode)->first() !== null);
            
            // Hitung nomor antrean berurutan per slot (1, 2, 3, dst.)
            $queueNumber = $this->bookingModel->where('slot_id', $slotId)->countAllResults() + 1;
            
            $bookingData = [
                'id' => $newBookingId,
                'booking_code' => $bookingCode,
                'customer_id' => $customerId,
                'slot_id' => $slotId,
                'booth_id' => $boothId,
                'print_option_id' => $printOptionId,  // Menggunakan data Daftar Cetak dari Admin
                'queue_number' => $queueNumber,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->bookingModel->insert($bookingData);
            
            $bookingId = $newBookingId;
            $this->session->set('booking_id', $bookingId);
        }

        // Cek apakah payment sudah ada
        $existingPayment = $this->paymentModel
            ->where('booking_id', $bookingId)
            ->first();

        if ($existingPayment) {
            // Update existing payment
            $updateData = [
                'amount' => $amount,
                'gateway_ref' => 'PAY-' . strtoupper(uniqid()),
                'status' => 'paid',
                'payment_method' => $paymentMethod ?? 'QRIS',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->paymentModel->update($existingPayment['id'], $updateData);
            $paymentId = $existingPayment['id'];
        } else {
            // Create new payment
            if (empty($paymentId)) {
                $paymentId = 'PAY' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            $paymentData = [
                'id' => $paymentId,
                'booking_id' => $bookingId,
                'print_option_id' => $printOptionId,  // Data dari Daftar Cetak Admin
                'amount' => $amount,
                'gateway_ref' => 'PAY-' . strtoupper(uniqid()),
                'status' => 'paid',
                'payment_method' => $paymentMethod ?? 'QRIS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->paymentModel->insert($paymentData);
        }

        // Update booking status menjadi paid
        $this->bookingModel->update($bookingId, [
            'status' => 'paid',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Simpan ke session
        $this->session->set([
            'konfirmasi_pembayaran_id' => $paymentId,
            'booking_id' => $bookingId,
            'payment_method' => $paymentMethod ?? 'QRIS',
            'payment_status' => 'paid',
            'payment_amount' => $amount
        ]);

        // Redirect ke halaman status booking
        return redirect()->to('/StatusBooking?payment_id=' . $paymentId . '&booking_id=' . $bookingId)
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * METHOD CONFIRM - Untuk konfirmasi pembayaran dari form
     * Ini yang akan dipanggil oleh form action
     */
    public function confirm()
    {
        // Ambil data dari POST
        $paymentId = $this->request->getPost('payment_id');
        $bookingId = $this->request->getPost('booking_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $amount = $this->request->getPost('amount');
        $status = $this->request->getPost('status');

        // Validasi
        if (empty($paymentId) || empty($amount)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data pembayaran tidak lengkap. Silahkan coba lagi.');
        }

        // Jika booking_id tidak ada, ambil dari session
        if (empty($bookingId) || !is_numeric($bookingId)) {
            $bookingId = $this->session->get('booking_id');
            
            if (empty($bookingId)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Session booking tidak ditemukan. Silahkan pilih frame terlebih dahulu.');
            }
        }

        // Cek apakah booking ada di database
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Booking tidak ditemukan. Silahkan pilih frame terlebih dahulu.');
        }

        // Cek apakah payment sudah ada
        $existingPayment = $this->paymentModel
            ->where('booking_id', $bookingId)
            ->first();

        if ($existingPayment) {
            // Update existing payment
            $updateData = [
                'amount' => $amount,
                'gateway_ref' => 'PAY-' . strtoupper(uniqid()),
                'status' => 'paid',
                'payment_method' => $paymentMethod ?? 'QRIS',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->paymentModel->update($existingPayment['id'], $updateData);
            $paymentId = $existingPayment['id'];
        } else {
            // Create new payment
            if (empty($paymentId)) {
                $paymentId = 'PAY' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            $paymentData = [
                'id' => $paymentId,
                'booking_id' => $bookingId,
                'print_option_id' => $this->session->get('selected_print_option_id') ?: null,
                'amount' => $amount,
                'gateway_ref' => 'PAY-' . strtoupper(uniqid()),
                'status' => 'paid',
                'payment_method' => $paymentMethod ?? 'QRIS',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->paymentModel->insert($paymentData);
        }

        // Update booking status menjadi paid
        $this->bookingModel->update($bookingId, [
            'status' => 'paid',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Simpan ke session
        $this->session->set([
            'konfirmasi_pembayaran_id' => $paymentId,
            'booking_id' => $bookingId,
            'payment_method' => $paymentMethod ?? 'QRIS',
            'payment_status' => 'paid',
            'payment_amount' => $amount
        ]);

        // Redirect ke halaman status booking
        return redirect()->to('/StatusBooking?payment_id=' . $paymentId . '&booking_id=' . $bookingId)
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function detail($bookingCode = null)
    {
        if (!$bookingCode) {
            return redirect()->to('payment')->with('error', 'Kode booking tidak ditemukan');
        }

        $booking = $this->bookingModel
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return redirect()->to('payment')->with('error', 'Booking tidak ditemukan');
        }

        $this->session->set('booking_id', $booking['id']);

        return redirect()->to('payment');
    }

    public function success($paymentId = null)
    {
        if (!$paymentId) {
            return redirect()->to('payment')->with('error', 'Payment ID tidak ditemukan');
        }

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->to('payment')->with('error', 'Payment tidak ditemukan');
        }

        return redirect()->to('/StatusBooking?payment_id=' . $paymentId . '&booking_id=' . $payment['booking_id'])
            ->with('success', 'Pembayaran berhasil!');
    }

    private function getBookingDetail($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return null;
        }

        $customer = $this->customerModel->find($booking['customer_id'] ?? 0);

        return [
            'id' => $booking['id'] ?? 0,
            'booking_code' => $booking['booking_code'] ?? 'N/A',
            'queue_number' => $booking['queue_number'] ?? null,
            'frame_id' => $booking['frame_id'] ?? null,
            'total_amount' => $booking['total_amount'] ?? 0,
            'status' => $booking['status'] ?? 'pending',
            'booking_date' => isset($booking['booking_date']) ? date('d F Y', strtotime($booking['booking_date'])) : date('d F Y'),
            'booking_time' => isset($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) : date('H:i'),
            'customer' => $customer ?: ['name' => 'N/A', 'email' => 'N/A', 'phone' => 'N/A'],
            'created_at' => $booking['created_at'] ?? date('Y-m-d H:i:s'),
        ];
    }

    private function createDummyBooking($amount = 45000)
    {
        $bookingCode = 'BK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        
        // Coba cari booking yang sudah ada di session
        $bookingId = $this->session->get('booking_id');
        
        if ($bookingId) {
            $existing = $this->bookingModel->find($bookingId);
            if ($existing) {
                return [
                    'id' => $existing['id'],
                    'booking_code' => $existing['booking_code'] ?? $bookingCode,
                    'total_amount' => $existing['total_amount'] ?? $amount,
                    'booking_date' => isset($existing['booking_date']) ? date('d F Y', strtotime($existing['booking_date'])) : date('d F Y'),
                    'booking_time' => isset($existing['booking_time']) ? date('H:i', strtotime($existing['booking_time'])) : date('H:i') . ' WIB',
                    'status' => $existing['status'] ?? 'pending'
                ];
            }
        }

        // Buat dummy tanpa insert ke database
        return [
            'id' => 1,
            'booking_code' => $bookingCode,
            'total_amount' => $amount,
            'booking_date' => date('d F Y'),
            'booking_time' => date('H:i') . ' WIB',
            'status' => 'pending'
        ];
    }

    private function getDummyFrames()
    {
        return [
            [
                'id' => 'B001',
                'name' => 'Frame Premium 1',
                'price' => 150000,
                'description' => 'Frame premium dengan kamera 4K dan lighting profesional',
                'capacity' => 4,
                'status' => 'available',
                'facilities' => ['Kamera 4K', 'Lighting Profesional', 'Background LED', 'Prop lengkap']
            ],
            [
                'id' => 'B002',
                'name' => 'Frame Premium 2',
                'price' => 150000,
                'description' => 'Frame premium dengan aksesoris lengkap dan ruang yang luas',
                'capacity' => 4,
                'status' => 'available',
                'facilities' => ['Kamera 4K', 'Lighting Profesional', 'Background Video', 'Prop lengkap', 'Monitor 32"']
            ],
            [
                'id' => 'B003',
                'name' => 'Frame Standar 1',
                'price' => 100000,
                'description' => 'Frame standar dengan kualitas gambar yang baik dan nyaman',
                'capacity' => 3,
                'status' => 'available',
                'facilities' => ['Kamera HD', 'Lighting Standar', 'Background Polos', 'Prop dasar']
            ],
            [
                'id' => 'B004',
                'name' => 'Frame Standar 2',
                'price' => 100000,
                'description' => 'Frame standar dengan desain minimalis dan modern',
                'capacity' => 3,
                'status' => 'available',
                'facilities' => ['Kamera HD', 'Lighting Standar', 'Background Polos', 'Aksesoris tambahan']
            ],
            [
                'id' => 'B005',
                'name' => 'Frame Family 1',
                'price' => 200000,
                'description' => 'Frame khusus keluarga dengan ruang yang luas dan nyaman',
                'capacity' => 6,
                'status' => 'available',
                'facilities' => ['Kamera 4K', 'Lighting Premium', 'Background LED', 'Prop lengkap', 'Ruang luas']
            ],
            [
                'id' => 'B006',
                'name' => 'Frame Couple 1',
                'price' => 180000,
                'description' => 'Frame romantis khusus untuk couple dengan dekorasi spesial',
                'capacity' => 2,
                'status' => 'available',
                'facilities' => ['Kamera 4K', 'Lighting Romantis', 'Background Dekorasi', 'Prop couple']
            ],
        ];
    }
}