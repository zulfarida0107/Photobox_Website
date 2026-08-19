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

        // Pemesanan terbaru dengan nama pelanggan (Maksimal 5 data per halaman)
        $currentPage        = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage            = 5;
        $totalBookingsCount = $bookingModel->countAllResults(false);
        $totalPages         = max(1, (int) ceil($totalBookingsCount / $perPage));

        $recentBookings = $bookingModel
            ->select('bookings.*, customers.name AS customer_name, print_options.copies AS print_copies')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('print_options',  'print_options.id = bookings.print_option_id',  'left')
            ->orderBy('bookings.created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

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
            'currentPage'           => $currentPage,
            'totalPages'            => $totalPages,
            'totalBookingsCount'    => $totalBookingsCount,
        ];

        return view('Admin/AdminDashboard/AdminDashboard', $data);
    }
}

