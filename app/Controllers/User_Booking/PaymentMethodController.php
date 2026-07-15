<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\PaymentModel;
use App\Models\BookingModel;
use App\Models\CustomerModel;

class PaymentMethodController extends BaseController
{
    protected $paymentModel;
    protected $bookingModel;
    protected $customerModel;
    protected $session;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data = [
            'title' => 'Payment Method - CuitBox Studio',
            'booking' => null,
            'payment' => null,
            'error' => null,
            'success' => null,
        ];

        // Cek session untuk booking_id
        if ($this->session->has('booking_id')) {
            $bookingId = $this->session->get('booking_id');
            $booking = $this->getBookingDetail($bookingId);
            if ($booking) {
                $data['booking'] = $booking;
                // Cek payment yang sudah ada
                $payment = $this->paymentModel
                    ->where('booking_id', $bookingId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if ($payment) {
                    $data['payment'] = $payment;
                }
            }
        }

        // Cek flashdata
        if ($this->session->getFlashdata('error')) {
            $data['error'] = $this->session->getFlashdata('error');
        }
        if ($this->session->getFlashdata('success')) {
            $data['success'] = $this->session->getFlashdata('success');
        }

        return view('User_Booking/payment_method', $data);
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

        // Simpan ke session
        $this->session->set('booking_id', $booking['id']);

        return redirect()->to('payment');
    }

    public function confirm()
    {
        $rules = [
            'booking_id' => 'required|numeric',
            'payment_method' => 'required',
            'amount' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon lengkapi data pembayaran');
        }

        $bookingId = $this->request->getPost('booking_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $amount = $this->request->getPost('amount');

        // Cek booking
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        // Generate ID untuk payment (karena useAutoIncrement = false)
        $paymentId = $this->generatePaymentId();

        // Cek apakah sudah ada payment
        $existingPayment = $this->paymentModel
            ->where('booking_id', $bookingId)
            ->first();

        if ($existingPayment) {
            // Update existing payment
            $this->paymentModel->update($existingPayment['id'], [
                'amount' => $amount,
                'gateway_ref' => 'PAY-' . strtoupper(uniqid()),
                'status' => 'pending',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $paymentId = $existingPayment['id'];
        } else {
            // Create new payment
            $paymentData = [
                'id' => $paymentId,
                'booking_id' => $bookingId,
                'print_option_id' => 1, // Default
                'amount' => $amount,
                'gateway_ref' => 'PAY-' . strtoupper(uniqid()),
                'status' => 'pending',
            ];

            $this->paymentModel->insert($paymentData);
        }

        // Simpan metode pembayaran ke session untuk ditampilkan di halaman sukses
        $this->session->set('payment_method', $paymentMethod);

        return redirect()->to('payment/success/' . $paymentId)
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function success($paymentId = null)
    {
        if (!$paymentId) {
            return redirect()->to('payment')->with('error', 'Data pembayaran tidak ditemukan');
        }

        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->to('payment')->with('error', 'Pembayaran tidak ditemukan');
        }

        // Update status payment menjadi success
        $this->paymentModel->update($paymentId, [
            'status' => 'success',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Update booking status
        $this->bookingModel->update($payment['booking_id'], [
            'status' => 'paid'
        ]);

        $booking = $this->getBookingDetail($payment['booking_id']);
        $paymentMethod = $this->session->get('payment_method') ?? 'Bank Transfer';

        $data = [
            'title' => 'Payment Success - CuitBox Studio',
            'payment' => $payment,
            'booking' => $booking,
            'payment_method' => $paymentMethod,
        ];

        return view('User_Booking/payment_success', $data);
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
            'status' => $booking['status'] ?? 'pending',
            'booking_date' => isset($booking['booking_date']) ? date('d F Y', strtotime($booking['booking_date'])) : date('d F Y'),
            'booking_time' => isset($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) : date('H:i'),
            'customer' => $customer ?: ['name' => 'N/A', 'email' => 'N/A', 'phone' => 'N/A'],
            'created_at' => $booking['created_at'] ?? date('Y-m-d H:i:s'),
        ];
    }

    private function generatePaymentId()
    {
        $lastId = $this->paymentModel
            ->orderBy('id', 'DESC')
            ->first();
        
        if ($lastId) {
            $lastNumber = (int) substr($lastId['id'], 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'PAY' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}