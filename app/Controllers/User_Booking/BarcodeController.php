<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use App\Models\BoothModel;
use App\Models\PackageModel;

class BarcodeController extends BaseController
{
    protected $bookingModel;
    protected $customerModel;
    protected $boothModel;
    protected $packageModel;
    protected $session;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->boothModel = new BoothModel();
        $this->packageModel = new PackageModel();
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
        $package = $this->packageModel->find($booking['package_id'] ?? 0);

        $statusMap = [
            'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'yellow'],
            'paid' => ['label' => 'Terbayar', 'color' => 'green'],
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
            'booking_date' => isset($booking['booking_date']) ? date('d F Y', strtotime($booking['booking_date'])) : date('d F Y'),
            'booking_time' => isset($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) : date('H:i'),
            'customer' => $customer ?: ['name' => 'N/A', 'email' => 'N/A', 'phone' => 'N/A'],
            'booth' => $booth ?: ['name' => 'N/A', 'location' => 'N/A'],
            'package' => $package ?: ['name' => 'N/A', 'duration' => '0', 'price' => 0],
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
}