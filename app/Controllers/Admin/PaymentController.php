<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaymentModel;

class PaymentController extends BaseController
{
    protected PaymentModel $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
    }

    // =========================================================================
    // INDEX — Daftar pembayaran + stats + filter + pagination
    // =========================================================================
    public function index()
    {
        $perPage       = 10;
        $currentPage   = max(1, (int) ($this->request->getGet('page')   ?? 1));
        $filterStatus  = $this->request->getGet('status') ?? '';
        $filterPeriod  = $this->request->getGet('period') ?? 'all'; // Default to all time so data is visible on load
        $paymentDate   = $this->request->getGet('payment_date') ?? '';
        $keyword       = $this->request->getGet('q') ?? '';

        // --- Rentang tanggal ---
        $hasDateFilter = false;
        if ($paymentDate !== '') {
            $dateFrom = $paymentDate . ' 00:00:00';
            $dateTo   = $paymentDate . ' 23:59:59';
            $hasDateFilter = true;
        } elseif ($filterPeriod === 'month') {
            $dateFrom = date('Y-m-01 00:00:00');
            $dateTo   = date('Y-m-t 23:59:59');
            $hasDateFilter = true;
        } elseif ($filterPeriod !== 'all') {
            $days     = (int) $filterPeriod ?: 7;
            $dateFrom = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
            $dateTo   = date('Y-m-d 23:59:59');
            $hasDateFilter = true;
        }

        // --- Query utama dengan JOIN ---
        $builder = $this->paymentModel
            ->select('payments.*, bookings.booking_code, print_options.copies AS print_option_copies, print_options.extra_price AS print_option_extra')
            ->join('bookings',     'bookings.id = payments.booking_id',           'left')
            ->join('print_options','print_options.id = payments.print_option_id', 'left');

        if ($hasDateFilter) {
            $builder->where('payments.created_at >=', $dateFrom)
                    ->where('payments.created_at <=', $dateTo);
        }

        if ($filterStatus !== '') {
            $builder->where('payments.status', $filterStatus);
        }

        if ($keyword !== '') {
            $builder->groupStart()
                ->like('payments.id', $keyword)
                ->orLike('bookings.booking_code', $keyword)
                ->orLike('payments.gateway_ref', $keyword)
                ->groupEnd();
        }

        $totalPayments = $builder->countAllResults(false);
        $totalPages    = max(1, (int) ceil($totalPayments / $perPage));

        $payments = $builder
            ->orderBy('payments.created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Statistik ---
        $statPaidQuery = $this->paymentModel->where('status', 'paid');
        if ($hasDateFilter) {
            $statPaidQuery->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }
        $statPaid = $statPaidQuery->countAllResults();

        $statFailedQuery = $this->paymentModel->where('status', 'failed');
        if ($hasDateFilter) {
            $statFailedQuery->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }
        $statFailed = $statFailedQuery->countAllResults();

        $statPendingQuery = $this->paymentModel->where('status', 'pending');
        if ($hasDateFilter) {
            $statPendingQuery->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }
        $statPending = $statPendingQuery->countAllResults();

        $statFailedPending = $statFailed + $statPending;

        // Total pendapatan (paid saja)
        $revenueBuilder = $this->paymentModel->db
            ->table('payments')
            ->select('SUM(amount) AS total')
            ->where('status', 'paid');
        if ($hasDateFilter) {
            $revenueBuilder->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }
        $revenueRow = $revenueBuilder->get()->getRowArray();
        $statTotalRevenue = $revenueRow['total'] ?? 0;

        // Konversi rate
        $statTotal      = $statPaid + $statFailedPending;
        $statConversion = $statTotal > 0 ? round(($statPaid / $statTotal) * 100, 1) : 0;

        // Add-on cetak
        $printAddonBuilder = $this->paymentModel
            ->where('print_option_id IS NOT NULL', null, false)
            ->where('status', 'paid');
        if ($hasDateFilter) {
            $printAddonBuilder->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }
        $statPrintAddon = $printAddonBuilder->countAllResults();

        $printProfitBuilder = $this->paymentModel->db
            ->table('payments')
            ->select('SUM(amount) AS total')
            ->where('print_option_id IS NOT NULL', null, false)
            ->where('status', 'paid');
        if ($hasDateFilter) {
            $printProfitBuilder->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }
        $printProfitRow = $printProfitBuilder->get()->getRowArray();
        $statPrintProfit = $printProfitRow['total'] ?? 0;

        $data = [
            'title'             => 'Manajemen Pembayaran | Admin Panel',
            'payments'          => $payments,
            'totalPayments'     => $totalPayments,
            'currentPage'       => $currentPage,
            'totalPages'        => $totalPages,
            'filterStatus'      => $filterStatus,
            'filterPeriod'      => $filterPeriod,
            'paymentDate'       => $paymentDate,
            'keyword'           => $keyword,
            'statTotalRevenue'  => $statTotalRevenue,
            'statPaid'          => $statPaid,
            'statFailedPending' => $statFailedPending,
            'statConversion'    => $statConversion,
            'statPrintAddon'    => $statPrintAddon,
            'statPrintProfit'   => $statPrintProfit,
        ];

        return view('Admin/Payment/PaymentManajemen', $data);
    }

    // =========================================================================
    // VERIFY — Verifikasi manual: ubah status jadi 'paid'
    // =========================================================================
    public function verify(string $id)
    {
        $payment = $this->paymentModel->find($id);

        if (!$payment) {
            return redirect()->to(base_url('admin/payments'))
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        $this->paymentModel->update($id, [
            'status'     => 'paid',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if (!empty($payment['booking_id'])) {
            $bookingModel = new \App\Models\BookingModel();
            $bookingModel->update($payment['booking_id'], [
                'status'     => 'paid',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->to(base_url('admin/payments'))
                ->with('error', "Gagal memverifikasi pembayaran #{$id}.");
        }

        $db->transCommit();

        return redirect()->to(base_url('admin/payments'))
            ->with('success', "Pembayaran #{$id} dan Booking terkait berhasil diverifikasi sebagai Paid.");
    }

    // =========================================================================
    // VERIFY BULK — Verifikasi semua yang pending/failed jadi paid
    // =========================================================================
    public function verifyBulk()
    {
        // Ambil filter periode dari query string agar bulk verify tidak global tanpa batas
        $filterPeriod = $this->request->getGet('period') ?? 'today';
        $paymentDate  = $this->request->getGet('payment_date') ?? '';

        $hasDateFilter = false;
        if ($paymentDate !== '') {
            $dateFrom      = $paymentDate . ' 00:00:00';
            $dateTo        = $paymentDate . ' 23:59:59';
            $hasDateFilter = true;
        } elseif ($filterPeriod === 'month') {
            $dateFrom      = date('Y-m-01 00:00:00');
            $dateTo        = date('Y-m-t 23:59:59');
            $hasDateFilter = true;
        } elseif ($filterPeriod !== 'all') {
            $days          = (int) $filterPeriod ?: 7;
            $dateFrom      = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
            $dateTo        = date('Y-m-d 23:59:59');
            $hasDateFilter = true;
        }

        $builder = $this->paymentModel->whereIn('status', ['pending', 'failed']);

        if ($hasDateFilter) {
            $builder->where('created_at >=', $dateFrom)->where('created_at <=', $dateTo);
        }

        $targetPayments = $builder->findAll();

        if (empty($targetPayments)) {
            return redirect()->to(base_url('admin/payments'))
                ->with('error', 'Tidak ada pembayaran pending/gagal yang perlu diverifikasi pada periode ini.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        $paymentIds = [];
        $bookingIds = [];
        foreach ($targetPayments as $p) {
            $paymentIds[] = $p['id'];
            if (!empty($p['booking_id'])) {
                $bookingIds[] = $p['booking_id'];
            }
        }

        $this->paymentModel->whereIn('id', $paymentIds)->set([
            'status'     => 'paid',
            'updated_at' => date('Y-m-d H:i:s')
        ])->update();

        if (!empty($bookingIds)) {
            $bookingModel = new \App\Models\BookingModel();
            $bookingModel->whereIn('id', array_unique($bookingIds))->set([
                'status'     => 'paid',
                'updated_at' => date('Y-m-d H:i:s')
            ])->update();
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->to(base_url('admin/payments'))
                ->with('error', 'Gagal memproses verifikasi massal.');
        }

        $db->transCommit();

        $periodLabel = match ($filterPeriod) {
            'today'  => 'Hari Ini',
            '7'      => '7 Hari Terakhir',
            'month'  => 'Bulan Ini',
            'all'    => 'Semua Waktu',
            default  => $filterPeriod,
        };
        if ($paymentDate !== '') {
            $periodLabel = 'Tanggal ' . $paymentDate;
        }

        $totalCount = count($paymentIds);
        return redirect()->to(base_url('admin/payments'))
            ->with('success', "Verifikasi massal selesai ({$totalCount} transaksi) untuk periode \"{$periodLabel}\". Status pembayaran dan booking berhasil diubah ke Paid.");
    }

    // =========================================================================
    // INVOICE — Cetak invoice PDF (placeholder — integrasikan library PDF)
    // =========================================================================
    public function invoice(string $id)
    {
        $payment = $this->paymentModel
            ->select('payments.*, bookings.booking_code, print_options.copies AS print_option_copies, print_options.extra_price AS print_option_extra')
            ->join('bookings',     'bookings.id = payments.booking_id',           'left')
            ->join('print_options','print_options.id = payments.print_option_id', 'left')
            ->find($id);

        if (!$payment || $payment['status'] !== 'paid') {
            return redirect()->to(base_url('admin/payments'))
                ->with('error', 'Invoice hanya tersedia untuk pembayaran yang sudah Paid.');
        }

        // TODO: Integrasikan library PDF (misal: DOMPDF) untuk generate invoice
        // Sementara tampilkan view invoice HTML
        return view('Admin/Payment/PaymentInvoice', [
            'title'   => 'Invoice #' . $id,
            'payment' => $payment,
        ]);
    }

    // =========================================================================
    // EXPORT — Ekspor data CSV
    // =========================================================================
    public function export()
    {
        $filterStatus = $this->request->getGet('status') ?? '';
        $filterPeriod = $this->request->getGet('period') ?? 'all'; // Default to all time so data is visible on load

        $hasDateFilter = false;
        if ($filterPeriod === 'month') {
            $dateFrom = date('Y-m-01 00:00:00');
            $dateTo   = date('Y-m-t 23:59:59');
            $hasDateFilter = true;
        } elseif ($filterPeriod !== 'all') {
            $days     = (int) $filterPeriod ?: 7;
            $dateFrom = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
            $dateTo   = date('Y-m-d 23:59:59');
            $hasDateFilter = true;
        }

        $builder = $this->paymentModel
            ->select('payments.id, bookings.booking_code, print_options.copies AS print_option_copies, print_options.extra_price AS print_option_extra, payments.amount, payments.gateway_ref, payments.status, payments.created_at')
            ->join('bookings',     'bookings.id = payments.booking_id',           'left')
            ->join('print_options','print_options.id = payments.print_option_id', 'left');

        if ($hasDateFilter) {
            $builder->where('payments.created_at >=', $dateFrom)
                    ->where('payments.created_at <=', $dateTo);
        }

        if ($filterStatus !== '') {
            $builder->where('payments.status', $filterStatus);
        }

        $data = $builder->orderBy('payments.created_at', 'DESC')->findAll();

        // Buat CSV
        $filename = 'Laporan_Pembayaran_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputs($output, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputs($output, "sep=;\n");

        $adminName = session('adminName') ?? session('admin_name') ?? 'Admin';
        $adminRole = session('adminRole') ?? session('admin_role') ?? 'Unknown Role';
        $adminEmail = session('adminEmail');
        
        if (!$adminEmail && session('adminId')) {
            $adminModel = new \App\Models\AdminModel();
            $adminData = $adminModel->find(session('adminId'));
            if ($adminData) {
                $adminEmail = $adminData['email'];
                $adminName = $adminData['name'];
            }
        }
        $adminEmail = $adminEmail ?? 'No Email';
        
        $penanggungJawab = $adminName . ' (' . $adminRole . ') - ' . $adminEmail;

        $currentTime = date('d M Y, H:i:s');
        $currentDate = date('d F Y');

        fputcsv($output, ['LAPORAN PEMBAYARAN'], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Penanggung Jawab: ' . $penanggungJawab], ';');
        fputcsv($output, ['Waktu Realtime: ' . $currentTime . ' WIB'], ';');
        fputcsv($output, ['Keterangan Tanggal: Diekspor pada ' . $currentDate], ';');
        fputcsv($output, [], ';');

        fputcsv($output, [
            'ID Pembayaran', 
            'Kode Booking', 
            'Opsi Cetak (Copy)', 
            'Harga Extra Cetak', 
            'Nominal', 
            'Gateway Ref', 
            'Status', 
            'Waktu'
        ], ';');

        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'],
                $row['booking_code'] ?? '',
                $row['print_option_copies'] ?? '-',
                $row['print_option_extra'] ?? 0,
                $row['amount'],
                $row['gateway_ref'] ?? '',
                $row['status'],
                $row['created_at'],
            ], ';');
        }
        fclose($output);
        exit;
    }
}
