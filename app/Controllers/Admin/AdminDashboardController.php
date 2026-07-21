<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\PaymentModel;
use App\Models\BookingModel;
use App\Models\BoothModel;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        $customerModel = new CustomerModel();
        $paymentModel  = new PaymentModel();
        $bookingModel  = new BookingModel();
        $boothModel    = new BoothModel();

        // Stats widget
        $totalCustomers       = $customerModel->countAll();
        
        $totalTransactions     = $paymentModel->countAllResults();
        $totalPaidTransactions = $paymentModel->where('status', 'paid')->countAllResults();
        
        $totalBookings         = $bookingModel->countAllResults();
        $totalPendingBookings  = $bookingModel->where('status', 'pending')->countAllResults();
        
        $totalBooths           = $boothModel->countAllResults();
        $totalActiveBooths     = $boothModel->where('status', 'active')->countAllResults();

        // Pemesanan terbaru dengan nama pelanggan
        $recentBookings = $bookingModel
            ->select('bookings.*, customers.name AS customer_name, packages.name AS package_name')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('packages',  'packages.id = bookings.package_id',  'left')
            ->orderBy('bookings.created_at', 'DESC')
            ->findAll(10);

        // Total pendapatan bulan ini
        $revenueRow = $paymentModel->db
            ->table('payments')
            ->select('SUM(amount) AS total')
            ->where('status', 'paid')
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->get()->getRowArray();
        $totalRevenueThisMonth = $revenueRow['total'] ?? 0;

        $data = [
            'title'                 => 'Dashboard | Admin Panel',
            'totalCustomers'        => $totalCustomers,
            'totalTransactions'     => $totalTransactions,
            'totalPaidTransactions' => $totalPaidTransactions,
            'totalBookings'         => $totalBookings,
            'totalPendingBookings'  => $totalPendingBookings,
            'totalBooths'           => $totalBooths,
            'totalActiveBooths'     => $totalActiveBooths,
            'recentBookings'        => $recentBookings,
            'totalRevenueThisMonth' => $totalRevenueThisMonth,
        ];

        return view('Admin/AdminDashboard/AdminDashboard', $data);
    }
}

