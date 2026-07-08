<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;

class PaymentController extends BaseController
{
    protected $bookingModel;
    protected $customerModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
    }

    /**
     * Display payment page
     * GET: /customer/payment
     */
    public function index()
    {
        // Get booking data from session
        $booking = session()->get('verified_booking');
        
        // If no booking in session, create demo data for testing
        if (!$booking) {
            $booking = [
                'id' => 'DEMO001',
                'booking_code' => 'BK20240501001',
                'customer_id' => 'CUST001',
                'package_id' => 1,
                'status' => 'verified'
            ];
        }
        
        // Package details
        $packages = [
            1 => ['name' => 'Basic Package', 'price' => 50000, 'duration' => 15],
            2 => ['name' => 'Premium Package', 'price' => 100000, 'duration' => 30],
            3 => ['name' => 'Family Package', 'price' => 150000, 'duration' => 45],
        ];
        
        $packageId = $booking['package_id'] ?? 1;
        $package = $packages[$packageId];
        
        $data = [
            'title' => 'Payment Method',
            'booking' => $booking,
            'package' => $package,
            'amount' => $package['price'],
            'qr_expiry' => 300, // 5 minutes
        ];
        
        return view('customer/payment_view', $data);
    }

    /**
     * Process payment
     * POST: /customer/payment/process
     */
    public function processPayment()
    {
        $bookingId = $this->request->getPost('booking_id');
        $paymentMethod = $this->request->getPost('payment_method');
        
        if (!$bookingId) {
            return redirect()->back()->with('error', 'Invalid booking');
        }
        
        // Update booking payment status
        $this->bookingModel->update($bookingId, [
            'payment_status' => 'paid',
            'status' => 'paid',
            'payment_method' => $paymentMethod ?? 'qris'
        ]);
        
        // Clear session
        session()->remove('verified_booking');
        
        return redirect()->to('/customer/choose-frame')
            ->with('success', 'Payment successful! Please choose your frame.');
    }

    /**
     * Check payment status (AJAX)
     * GET: /customer/payment/status
     */
    public function checkStatus()
    {
        $bookingId = $this->request->getGet('booking_id');
        
        if (!$bookingId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid booking']);
        }
        
        $booking = $this->bookingModel->find($bookingId);
        
        return $this->response->setJSON([
            'status' => true,
            'payment_status' => $booking['payment_status'] ?? 'pending',
            'booking_status' => $booking['status'] ?? 'pending'
        ]);
    }
}