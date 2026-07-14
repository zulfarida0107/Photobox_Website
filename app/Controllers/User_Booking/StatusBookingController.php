<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\SlotModel;
use App\Models\BoothModel;
use App\Models\PackageModel;

class StatusBookingController extends BaseController  // ← Ubah ini
{
    protected $bookingModel;
    protected $customerModel;
    protected $slotModel;
    protected $boothModel;
    protected $packageModel;
    protected $session;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->slotModel = new SlotModel();
        $this->boothModel = new BoothModel();
        $this->packageModel = new PackageModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data = [
            'title' => 'Status Booking - CuitBox Studio',
            'booking' => null,
            'error' => null,
            'success' => null
        ];

        // Cek flashdata
        if ($this->session->getFlashdata('success')) {
            $data['success'] = $this->session->getFlashdata('success');
        }
        if ($this->session->getFlashdata('error')) {
            $data['error'] = $this->session->getFlashdata('error');
        }

        if ($this->session->has('booking_id')) {
            $bookingId = $this->session->get('booking_id');
            $booking = $this->getBookingDetail($bookingId);
            if ($booking) {
                $data['booking'] = $booking;
            }
        }

        return view('user_booking/Status_Booking', $data);
    }

    public function store()
    {
        $rules = [
            'booking_code' => 'required|min_length[5]|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon periksa kembali kode booking Anda');
        }

        $bookingCode = $this->request->getPost('booking_code');
        
        $booking = $this->bookingModel
            ->where('booking_code', $bookingCode)
            ->first();

        if (!$booking) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode booking tidak ditemukan. Silakan periksa kembali kode Anda.');
        }

        $this->session->set('booking_id', $booking['id']);
        
        return redirect()->to('StatusBooking')
            ->with('success', '✅ Berhasil menemukan booking dengan kode: ' . $bookingCode);
    }

    private function getBookingDetail($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return null;
        }

        $customer = $this->customerModel->find($booking['customer_id'] ?? 0);
        $slot = $this->slotModel->find($booking['slot_id'] ?? 0);
        $booth = $this->boothModel->find($booking['booth_id'] ?? 0);
        $package = $this->packageModel->find($booking['package_id'] ?? 0);

        $statusMap = [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'yellow', 'icon' => 'hourglass_top'],
            'paid' => ['label' => 'Terbayar Penuh', 'color' => 'green', 'icon' => 'check_circle'],
            'confirmed' => ['label' => 'Dikonfirmasi', 'color' => 'blue', 'icon' => 'verified'],
            'completed' => ['label' => 'Selesai', 'color' => 'purple', 'icon' => 'celebration'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'red', 'icon' => 'cancel']
        ];

        $statusKey = $booking['status'] ?? 'pending';
        $status = $statusMap[$statusKey] ?? ['label' => ucfirst($statusKey), 'color' => 'gray', 'icon' => 'info'];

        return [
            'id' => $booking['id'] ?? 0,
            'booking_code' => $booking['booking_code'] ?? 'N/A',
            'queue_number' => $booking['queue_number'] ?? null,
            'status' => $booking['status'] ?? 'pending',
            'status_label' => $status['label'],
            'status_color' => $status['color'],
            'status_icon' => $status['icon'],
            'booking_date' => isset($booking['booking_date']) ? date('d F Y', strtotime($booking['booking_date'])) : date('d F Y'),
            'booking_time' => isset($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) : date('H:i'),
            'customer' => $customer ?: ['name' => 'N/A', 'email' => 'N/A', 'phone' => 'N/A'],
            'slot' => $slot ?: ['slot_time' => 'N/A', 'day' => 'N/A'],
            'booth' => $booth ?: ['name' => 'N/A', 'location' => 'N/A'],
            'package' => $package ?: ['name' => 'N/A', 'duration' => '0', 'price' => 0],
            'created_at' => $booking['created_at'] ?? date('Y-m-d H:i:s'),
            'updated_at' => $booking['updated_at'] ?? date('Y-m-d H:i:s')
        ];
    }

    public function clearSession()
    {
        $this->session->remove('booking_id');
        return redirect()->to('StatusBooking')
            ->with('success', 'Anda telah keluar dari halaman status booking');
    }
}