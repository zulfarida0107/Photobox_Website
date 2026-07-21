<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\SlotModel;
use App\Models\BoothModel;
use App\Models\PackageModel;
use App\Models\PaymentModel;

class StatusBookingController extends BaseController
{
    protected $bookingModel;
    protected $customerModel;
    protected $slotModel;
    protected $boothModel;
    protected $packageModel;
    protected $paymentModel;
    protected $session;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->slotModel = new SlotModel();
        $this->boothModel = new BoothModel();
        $this->packageModel = new PackageModel();
        $this->paymentModel = new PaymentModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data = [
            'title' => 'Status Booking'
        ];

        $paymentId = $this->request->getGet('payment_id');

        if ($paymentId) {
            $payment = $this->paymentModel->find($paymentId);
            if ($payment) {
                $data['payment'] = $payment;
            }
        }
        return view('user_booking/Status_Booking', $data);
    }

    public function store()
    {
        return redirect()->to('barcode');
    }
}