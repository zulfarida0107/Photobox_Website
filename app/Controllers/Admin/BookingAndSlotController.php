<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\SlotModel;

class BookingAndSlotController extends BaseController
{
    protected BookingModel $bookingModel;
    protected SlotModel    $slotModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->slotModel    = new SlotModel();
    }

    // =========================================================================
    // INDEX — Halaman utama (slot hari ini + daftar booking)
    // =========================================================================
    public function index()
    {
        $period      = $this->request->getGet('period') ?? 'today';
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $keyword     = $this->request->getGet('q') ?? '';
        $slotKeyword = $this->request->getGet('slot_q') ?? '';
        $slotDate    = $this->request->getGet('slot_date') ?? '';
        $perPage     = 10;
        $today       = date('Y-m-d');
        
        // Menentukan rentang tanggal berdasarkan filter period
        $dateStart = $today;
        $dateEnd   = $today;
        
        if ($period === 'week') {
            $dateStart = date('Y-m-d', strtotime('monday this week'));
            $dateEnd   = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($period === 'month') {
            $dateStart = date('Y-m-01');
            $dateEnd   = date('Y-m-t');
        } elseif ($period === 'all') {
            $dateStart = '2000-01-01'; // Masa lalu
            $dateEnd   = '2099-12-31'; // Masa depan
        }

        // --- Slot Operasional ---
        $slotsBuilder = $this->slotModel
            ->select('slots.*, booths.name AS booth_name')
            ->join('booths', 'booths.id = slots.booth_id', 'left');

        if ($slotDate !== '') {
            $slotsBuilder->where('slots.slot_date', $slotDate);
        } else {
            $slotsBuilder->where('slots.slot_date >=', $dateStart)
                         ->where('slots.slot_date <=', $dateEnd);
        }

        if ($slotKeyword !== '') {
            $slotsBuilder->groupStart()
                ->like('slots.id', $slotKeyword)
                ->orLike('booths.name', $slotKeyword)
                ->groupEnd();
        }

        $slots = $slotsBuilder->orderBy('slots.slot_date', 'ASC')
            ->orderBy('slots.start_time', 'ASC')
            ->findAll();

        $totalAvailableSlots = array_reduce($slots, function ($carry, $slot) {
            return $carry + ($slot['booked_count'] < $slot['max_bookings'] ? 1 : 0);
        }, 0);

        // --- Booking berdasarkan periode ---
        $bookingBuilder = $this->bookingModel
            ->select('bookings.*, customers.name AS customer_name, booths.name AS booth_name, packages.name AS package_name')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('slots',     'slots.id = bookings.slot_id', 'left')
            ->join('booths',    'booths.id = bookings.booth_id', 'left')
            ->join('packages',  'packages.id = bookings.package_id', 'left')
            ->where('slots.slot_date >=', $dateStart)
            ->where('slots.slot_date <=', $dateEnd);

        if ($keyword !== '') {
            $bookingBuilder->groupStart()
                ->like('bookings.booking_code', $keyword)
                ->orLike('customers.name', $keyword)
                ->orLike('packages.name', $keyword)
                ->groupEnd();
        }

        $totalBookings = $bookingBuilder->countAllResults(false);
        $totalPages    = max(1, (int) ceil($totalBookings / $perPage));

        $bookings = $bookingBuilder
            ->orderBy('bookings.created_at', 'DESC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        // --- Quick Stats ---
        $statTotalReservasi = $this->bookingModel->countAll();
        $statCancelled      = $this->bookingModel->where('status', 'cancelled')->countAllResults();

        // Tingkat okupansi: booked_count / max_bookings * 100 (slot hari ini)
        $totalMax    = array_sum(array_column($slots, 'max_bookings'));
        $totalBooked = array_sum(array_column($slots, 'booked_count'));
        $statOkupansi = $totalMax > 0
            ? round(($totalBooked / $totalMax) * 100, 1) . '%'
            : '0%';
        $statTopBoothPct = $totalMax > 0
            ? round(($totalBooked / $totalMax) * 100)
            : 0;

        // Booth teraktif hari ini (booth dengan jumlah booking terbanyak)
        $topBooth       = $this->bookingModel
            ->select('booths.name AS booth_name, COUNT(bookings.id) AS total')
            ->join('slots',  'slots.id = bookings.slot_id', 'left')
            ->join('booths', 'booths.id = bookings.booth_id', 'left')
            ->where('slots.slot_date', $today)
            ->groupBy('bookings.booth_id')
            ->orderBy('total', 'DESC')
            ->first();
        $statTopBooth = $topBooth['booth_name'] ?? 'N/A';

        $data = [
            'title'               => 'Manajemen Reservasi | Admin Panel',
            'slots'               => $slots,
            'totalAvailableSlots' => $totalAvailableSlots,
            'bookings'            => $bookings,
            'totalBookings'       => $totalBookings,
            'currentPage'         => $currentPage,
            'totalPages'          => $totalPages,
            'period'              => $period,
            'keyword'             => $keyword,
            'slotKeyword'         => $slotKeyword,
            'slotDate'            => $slotDate,
            'statTotalReservasi'  => $statTotalReservasi,
            'statOkupansi'        => $statOkupansi,
            'statCancelled'       => $statCancelled,
            'statTopBooth'        => $statTopBooth,
            'statTopBoothPct'     => $statTopBoothPct,
        ];

        return view('Admin/BookingAndSlot/BookingAndSlotManajemen', $data);
    }

    // =========================================================================
    // SHOW — Detail satu booking
    // =========================================================================
    public function show(string $id)
    {
        $booking = $this->bookingModel
            ->select('bookings.*, customers.name AS customer_name, booths.name AS booth_name, packages.name AS package_name')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('slots',     'slots.id = bookings.slot_id', 'left')
            ->join('booths',    'booths.id = bookings.booth_id', 'left')
            ->join('packages',  'packages.id = bookings.package_id', 'left')
            ->find($id);

        if (!$booking) {
            return redirect()->to(base_url('admin/bookings'))
                ->with('error', 'Booking tidak ditemukan.');
        }

        return view('Admin/BookingAndSlot/BookingDetail', ['title' => 'Detail Booking', 'booking' => $booking]);
    }

    // =========================================================================
    // EDIT — Form edit booking
    // =========================================================================
    public function edit(string $id)
    {
        $booking = $this->bookingModel
            ->select('bookings.*, customers.name AS customer_name, customers.email AS customer_email, customers.phone AS customer_phone, packages.name AS package_name')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('packages',  'packages.id = bookings.package_id',  'left')
            ->find($id);

        if (!$booking) {
            return redirect()->to(base_url('admin/bookings'))
                ->with('error', 'Booking tidak ditemukan.');
        }

        if (($booking['edit_count'] ?? 0) >= 1) {
            return redirect()->to(base_url('admin/bookings'))
                ->with('error', 'Booking ini sudah pernah diubah sebelumnya dan tidak dapat diubah lagi.');
        }

        $slots = $this->slotModel
            ->select('slots.*, booths.name AS booth_name')
            ->join('booths', 'booths.id = slots.booth_id', 'left')
            ->where('slot_date >=', date('Y-m-d'))
            ->findAll();

        return view('Admin/BookingAndSlot/BookingForm', [
            'title'      => 'Edit Booking',
            'booking'    => $booking,
            'slots'      => $slots,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // UPDATE — Simpan perubahan booking
    // =========================================================================
    public function update(string $id)
    {
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return redirect()->to(base_url('admin/bookings'))
                ->with('error', 'Booking tidak ditemukan.');
        }

        if (($booking['edit_count'] ?? 0) >= 1) {
            return redirect()->to(base_url('admin/bookings'))
                ->with('error', 'Booking ini sudah pernah diubah sebelumnya dan tidak dapat diubah lagi.');
        }

        $rules = [
            'slot_id'      => 'required',
            'queue_number' => 'required|max_length[10]',
            'status'       => 'required|in_list[pending,paid,completed,cancelled]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->bookingModel->update($id, [
            'slot_id'      => $this->request->getPost('slot_id'),
            'queue_number' => $this->request->getPost('queue_number'),
            'status'       => $this->request->getPost('status'),
            'edit_count'   => ($booking['edit_count'] ?? 0) + 1,
        ]);

        return redirect()->to(base_url('admin/bookings'))
            ->with('success', 'Booking berhasil diperbarui.');
    }

    // =========================================================================
    // CANCEL — Batalkan booking
    // =========================================================================
    public function cancel(string $id)
    {
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return redirect()->to(base_url('admin/bookings'))
                ->with('error', 'Booking tidak ditemukan.');
        }

        $this->bookingModel->update($id, ['status' => 'cancelled']);

        // Kurangi booked_count pada slot terkait
        $slot = $this->slotModel->find($booking['slot_id']);
        if ($slot && $slot['booked_count'] > 0) {
            $this->slotModel->update($booking['slot_id'], [
                'booked_count' => $slot['booked_count'] - 1,
            ]);
        }

        return redirect()->to(base_url('admin/bookings'))
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    // =========================================================================
    // SLOTS INDEX — Daftar semua slot (kalender slot)
    // =========================================================================
    public function slots()
    {
        $currentPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage     = 10;

        $slotsBuilder = $this->slotModel
            ->select('slots.*, booths.name AS booth_name')
            ->join('booths', 'booths.id = slots.booth_id', 'left');

        $totalSlots  = $slotsBuilder->countAllResults(false);
        $totalPages  = max(1, (int) ceil($totalSlots / $perPage));

        $slots = $slotsBuilder
            ->orderBy('slots.slot_date', 'ASC')
            ->orderBy('slots.start_time', 'ASC')
            ->findAll($perPage, ($currentPage - 1) * $perPage);

        return view('Admin/BookingAndSlot/SlotKalender', [
            'title'       => 'Kalender Slot | Admin Panel',
            'slots'       => $slots,
            'totalSlots'  => $totalSlots,
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
        ]);
    }

    // =========================================================================
    // SLOT CREATE — Form slot baru
    // =========================================================================
    public function createSlot()
    {
        $boothModel = new \App\Models\BoothModel();
        $booths = $boothModel->where('status', 'active')->findAll();

        return view('Admin/BookingAndSlot/SlotForm', [
            'title'      => 'Buat Slot Baru | Admin Panel',
            'booths'     => $booths,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // =========================================================================
    // SLOT STORE — Simpan slot baru
    // =========================================================================
    public function storeSlot()
    {
        $rules = [
            'booth_id'     => 'required',
            'slot_date'    => 'required|valid_date',
            'start_time'   => 'required',
            'max_bookings' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate ID unik slot: SL-YYYYMMDD-XXX
        $dateStr   = date('Ymd');
        $lastCount = $this->slotModel->like('id', "SL-{$dateStr}-")->countAllResults();
        $newId     = sprintf('SL-%s-%03d', $dateStr, $lastCount + 1);

        $this->slotModel->insert([
            'id'           => $newId,
            'booth_id'     => $this->request->getPost('booth_id'),
            'slot_date'    => $this->request->getPost('slot_date'),
            'start_time'   => $this->request->getPost('start_time'),
            'max_bookings' => (int) $this->request->getPost('max_bookings'),
            'booked_count' => 0,
        ]);

        return redirect()->to(base_url('admin/bookings'))
            ->with('success', 'Slot baru berhasil dibuat.');
    }

    // =========================================================================
    // EXPORT — Ekspor data booking CSV
    // =========================================================================
    public function export()
    {
        $period = $this->request->getGet('period') ?? 'today';
        $today  = date('Y-m-d');
        
        $dateStart = $today;
        $dateEnd   = $today;
        
        if ($period === 'week') {
            $dateStart = date('Y-m-d', strtotime('monday this week'));
            $dateEnd   = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($period === 'month') {
            $dateStart = date('Y-m-01');
            $dateEnd   = date('Y-m-t');
        } elseif ($period === 'all') {
            $dateStart = '2000-01-01';
            $dateEnd   = '2099-12-31';
        }

        $bookingBuilder = $this->bookingModel
            ->select('bookings.*, customers.name AS customer_name, booths.name AS booth_name, packages.name AS package_name')
            ->join('customers', 'customers.id = bookings.customer_id', 'left')
            ->join('slots',     'slots.id = bookings.slot_id', 'left')
            ->join('booths',    'booths.id = bookings.booth_id', 'left')
            ->join('packages',  'packages.id = bookings.package_id', 'left')
            ->where('slots.slot_date >=', $dateStart)
            ->where('slots.slot_date <=', $dateEnd);

        $keyword = $this->request->getGet('q') ?? '';
        if ($keyword !== '') {
            $bookingBuilder->groupStart()
                ->like('bookings.booking_code', $keyword)
                ->orLike('customers.name', $keyword)
                ->orLike('packages.name', $keyword)
                ->groupEnd();
        }

        $data = $bookingBuilder->orderBy('bookings.created_at', 'DESC')->findAll();

        // Buat CSV
        $filename = 'Laporan_Reservasi_' . date('Ymd_His') . '.csv';
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

        fputcsv($output, ['LAPORAN RESERVASI'], ';');
        fputcsv($output, [], ';');
        fputcsv($output, ['Penanggung Jawab: ' . $penanggungJawab], ';');
        fputcsv($output, ['Waktu Realtime: ' . $currentTime . ' WIB'], ';');
        fputcsv($output, ['Keterangan Tanggal: Diekspor pada ' . $currentDate], ';');
        fputcsv($output, [], ';');

        fputcsv($output, [
            'ID Booking', 
            'Kode Booking', 
            'Nama Pelanggan', 
            'ID Slot', 
            'Nama Booth', 
            'Nama Paket', 
            'Nomor Antrean', 
            'Status', 
            'Waktu Dibuat'
        ], ';');

        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'],
                $row['booking_code'],
                $row['customer_name'] ?? '-',
                $row['slot_id'],
                $row['booth_name'] ?? '-',
                $row['package_name'] ?? '-',
                $row['queue_number'],
                strtoupper($row['status']),
                $row['created_at'],
            ], ';');
        }
        fclose($output);
        exit;
    }
}
