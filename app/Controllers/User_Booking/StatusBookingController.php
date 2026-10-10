<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\SlotModel;
use App\Models\BoothModel;
use App\Models\PrintOptionModel;
use App\Models\PaymentModel;
use App\Models\FrameModel;

class StatusBookingController extends BaseController
{
    protected $bookingModel;
    protected $customerModel;
    protected $slotModel;
    protected $boothModel;
    protected $printOptionModel;
    protected $paymentModel;
    protected $frameModel;
    protected $session;

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

    public function index()
    {
        $data = [
            'title'   => 'Status Booking - CuitBox Studio',
            'booking' => null,
            'payment' => null,
        ];

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

        // Live Fallback Midtrans Status Inquiry jika payment masih pending
        if ($payment && $payment['status'] === 'pending') {
            $serverKey = config('Midtrans')->serverKey ?? (string) env('midtrans.serverKey', '');
            if (!empty($serverKey)) {
                try {
                    \Midtrans\Config::$serverKey    = $serverKey;
                    \Midtrans\Config::$isProduction = (bool) (config('Midtrans')->isProduction ?? false);

                    $statusObj = \Midtrans\Transaction::status($payment['id']);
                    $txStatus  = $statusObj->transaction_status ?? '';
                    $fraud     = $statusObj->fraud_status ?? 'accept';

                    $db = \Config\Database::connect();
                    $db->transBegin();

                    if ($txStatus === 'settlement' || ($txStatus === 'capture' && $fraud === 'accept')) {
                        $this->paymentModel->update($payment['id'], [
                            'status'         => 'paid',
                            'payment_method' => $statusObj->payment_type ?? 'Midtrans',
                            'updated_at'     => date('Y-m-d H:i:s'),
                        ]);

                        if (!empty($payment['booking_id'])) {
                            $this->bookingModel->update($payment['booking_id'], [
                                'status'     => 'paid',
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    } elseif (in_array($txStatus, ['deny', 'cancel', 'expire'], true)) {
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

                    if ($db->transStatus() === false) {
                        $db->transRollback();
                    } else {
                        $db->transCommit();
                    }

                    // Reload updated payment
                    $payment = $this->paymentModel->find($payment['id']);
                } catch (\Throwable $e) {
                    // Jika transaksi belum ditemukan di Midtrans atau offline, biarkan pending
                    log_message('notice', '[Midtrans Live Inquiry Notice] ' . $e->getMessage());
                }
            }
        }

        if ($bookingId) {
            $this->session->set('booking_id', $bookingId);
            $bookingDetail = $this->getBookingDetail($bookingId);
            $data['booking'] = $bookingDetail;
        }

        $data['payment'] = $payment;

        return view('User_Booking/Status_Booking', $data);
    }

    public function store()
    {
        $bookingId = $this->request->getPost('booking_id') ?? $this->session->get('booking_id');
        if ($bookingId) {
            $this->session->set('booking_id', $bookingId);
        }
        return redirect()->to('barcode');
    }

    private function getBookingDetail($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            return null;
        }

        $customer    = $this->customerModel->find($booking['customer_id'] ?? 0);
        $booth       = $this->boothModel->find($booking['booth_id'] ?? 0);
        $printOption = $this->printOptionModel->find($booking['print_option_id'] ?? 0);

        // Fetch slot details
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

        $statusMap = [
            'paid'      => ['label' => 'Terbayar & Terkonfirmasi', 'badge' => 'bg-emerald-100 text-emerald-800 border border-emerald-300', 'icon' => 'check_circle'],
            'completed' => ['label' => 'Sesi Selesai',             'badge' => 'bg-purple-100 text-purple-800 border border-purple-300',   'icon' => 'task_alt'],
            'pending'   => ['label' => 'Menunggu Pembayaran',      'badge' => 'bg-amber-100 text-amber-800 border border-amber-300',     'icon' => 'hourglass_empty'],
            'cancelled' => ['label' => 'Dibatalkan',               'badge' => 'bg-red-100 text-red-800 border border-red-300',           'icon' => 'cancel'],
        ];

        $statusKey = $booking['status'] ?? 'pending';
        $meta = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'badge' => 'bg-gray-100 text-gray-800', 'icon' => 'info'];

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