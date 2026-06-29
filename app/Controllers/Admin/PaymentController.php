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
        $filterPeriod  = $this->request->getGet('period') ?? '7'; // hari

        // --- Rentang tanggal ---
        if ($filterPeriod === 'month') {
            $dateFrom = date('Y-m-01 00:00:00');
            $dateTo   = date('Y-m-t 23:59:59');
        } else {
            $days     = (int) $filterPeriod ?: 7;
            $dateFrom = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
            $dateTo   = date('Y-m-d 23:59:59');
        }

        // --- Query utama dengan JOIN ---
        $builder = $this->paymentModel
            ->select('payments.*, bookings.booking_code, print_options.copies AS print_option_copies, print_options.extra_price AS print_option_extra')
            ->join('bookings',     'bookings.id = payments.booking_id',           'left')
            ->join('print_options','print_options.id = payments.print_option_id', 'left')
            ->where('payments.created_at >=', $dateFrom)
            ->where('payments.created_at <=', $dateTo);

        if ($filterStatus !== '') {
            $builder->where('payments.status', $filterStatus);
        }

        $totalPayments = $builder->countAllResults(false);
        $totalPages    = max(1, (int) ceil($totalPayments / $perPage));

        // PENTING: countAllResults() mengubah SELECT menjadi COUNT(*), jadi kita harus set ulang SELECT-nya
        $builder->select('payments.*, bookings.booking_code, print_options.copies AS print_option_copies, print_options.extra_price AS print_option_extra');
        
        $payments = $builder
            ->orderBy('payments.created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Statistik ---
        $statPaid = $this->paymentModel
            ->where('status', 'paid')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo)
            ->countAllResults();

        $statFailed = $this->paymentModel
            ->where('status', 'failed')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo)
            ->countAllResults();

        $statPending = $this->paymentModel
            ->where('status', 'pending')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo)
            ->countAllResults();

        $statFailedPending = $statFailed + $statPending;

        // Total pendapatan (paid saja)
        $revenueRow = $this->paymentModel->db
            ->table('payments')
            ->select('SUM(amount) AS total')
            ->where('status', 'paid')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo)
            ->get()->getRowArray();
        $statTotalRevenue = $revenueRow['total'] ?? 0;

        // Konversi rate
        $statTotal      = $statPaid + $statFailedPending;
        $statConversion = $statTotal > 0 ? round(($statPaid / $statTotal) * 100, 1) : 0;

        // Add-on cetak
        $statPrintAddon  = $this->paymentModel
            ->where('print_option_id IS NOT NULL', null, false)
            ->where('status', 'paid')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo)
            ->countAllResults();

        $printProfitRow = $this->paymentModel->db
            ->table('payments')
            ->select('SUM(amount) AS total')
            ->where('print_option_id IS NOT NULL', null, false)
            ->where('status', 'paid')
            ->where('created_at >=', $dateFrom)
            ->where('created_at <=', $dateTo)
            ->get()->getRowArray();
        $statPrintProfit = $printProfitRow['total'] ?? 0;

        $data = [
            'title'             => 'Manajemen Pembayaran | Admin Panel',
            'payments'          => $payments,
            'totalPayments'     => $totalPayments,
            'currentPage'       => $currentPage,
            'totalPages'        => $totalPages,
            'filterStatus'      => $filterStatus,
            'filterPeriod'      => $filterPeriod,
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

        $this->paymentModel->update($id, ['status' => 'paid']);

        return redirect()->to(base_url('admin/payments'))
            ->with('success', "Pembayaran #{$id} berhasil diverifikasi sebagai Paid.");
    }

    // =========================================================================
    // VERIFY BULK — Verifikasi semua yang pending/failed jadi paid
    // =========================================================================
    public function verifyBulk()
    {
        $this->paymentModel->whereIn('status', ['pending', 'failed'])
            ->set(['status' => 'paid'])
            ->update();

        return redirect()->to(base_url('admin/payments'))
            ->with('success', 'Semua pembayaran pending/gagal berhasil diverifikasi massal.');
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
        $filterPeriod = $this->request->getGet('period') ?? '7';

        if ($filterPeriod === 'month') {
            $dateFrom = date('Y-m-01 00:00:00');
            $dateTo   = date('Y-m-t 23:59:59');
        } else {
            $days     = (int) $filterPeriod ?: 7;
            $dateFrom = date('Y-m-d 00:00:00', strtotime("-{$days} days"));
            $dateTo   = date('Y-m-d 23:59:59');
        }

        $builder = $this->paymentModel
            ->select('payments.id, bookings.booking_code, print_options.copies AS print_option_copies, print_options.extra_price AS print_option_extra, payments.amount, payments.gateway_ref, payments.status, payments.created_at')
            ->join('bookings',     'bookings.id = payments.booking_id',           'left')
            ->join('print_options','print_options.id = payments.print_option_id', 'left')
            ->where('payments.created_at >=', $dateFrom)
            ->where('payments.created_at <=', $dateTo);

        if ($filterStatus !== '') {
            $builder->where('payments.status', $filterStatus);
        }

        $data = $builder->orderBy('payments.created_at', 'DESC')->findAll();

        // Buat CSV
        $filename = 'payments_export_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // Tambahkan UTF-8 BOM agar karakter khusus terbaca dengan benar di Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Tambahkan deklarasi separator khusus agar Excel otomatis memisahkan kolom (karena pengaturan region Indonesia biasanya menggunakan titik koma)
        fputs($output, "sep=,\n");
        
        fputcsv($output, ['ID Pembayaran', 'Kode Booking', 'Opsi Cetak (Copy)', 'Harga Extra Cetak', 'Nominal', 'Gateway Ref', 'Status', 'Waktu']);
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
            ]);
        }
        fclose($output);
        exit;
    }
}
