<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\SlotModel;
use App\Models\FrameModel;
use App\Models\CustomerModel;

class ScheduleController extends BaseController
{
    protected $slotModel;
    protected $frameModel;
    protected $customerModel;

    public function __construct()
    {
        $this->slotModel = new SlotModel();
        $this->frameModel = new FrameModel();
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $customerId = session()->get('customer_id');
        $boothId = $this->request->getGet('booth_id') ?? session()->get('selected_booth_id');
        
        $boothModel = new \App\Models\BoothModel();
        $selectedBooth = null;
        if ($boothId) {
            $selectedBooth = $boothModel->find($boothId);
        }
        
        if (!$selectedBooth) {
            // Ambil booth aktif pertama sebagai default atau redirect ke pilih booth
            $firstBooth = $boothModel->where('status !=', 'maintenance')->first();
            if ($firstBooth) {
                session()->set('selected_booth_id', $firstBooth['id']);
                session()->set('selected_booth_name', $firstBooth['name']);
                session()->set('selected_booth_price', $firstBooth['price'] ?? 0);
                $selectedBooth = $firstBooth;
            } else {
                return redirect()->to('/booth')->with('info', 'Silakan pilih booth terlebih dahulu.');
            }
        }

        $data = [
            'title'         => 'Schedule Booking - CuitBox Studio',
            'frames'        => $this->frameModel->findAll(),
            'customer'      => $customerId ? $this->customerModel->find($customerId) : null,
            'selectedBooth' => $selectedBooth
        ];

        return view('User_Booking/schedule', $data);
    }

    // ===== MENERIMA DATA DARI FORM REGISTER =====
    public function store()
    {
        // Ambil data dari form
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
        $countryCode = $this->request->getPost('country_code') ?? '+62';

        // Validasi data
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[customers.email]',
            'phone' => 'required|min_length[11]|max_length[15]|regex_match[/^[0-9]+$/]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        $messages = [
            'name' => [
                'required' => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
                'max_length' => 'Nama lengkap maksimal 100 karakter.'
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email sudah terdaftar. Silakan gunakan email lain.'
            ],
            'phone' => [
                'required' => 'Nomor telepon wajib diisi.',
                'min_length' => 'Nomor telepon minimal 11 digit.',
                'max_length' => 'Nomor telepon maksimal 15 digit.',
                'regex_match' => 'Nomor telepon harus berupa angka.'
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches' => 'Konfirmasi password tidak sesuai dengan password.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Generate ID Customer secara aman & unik
        $existingCustomers = $this->customerModel->like('id', 'C', 'after')->findAll();
        $maxNum = 0;
        foreach ($existingCustomers as $c) {
            if (preg_match('/^C(\d+)$/i', $c['id'], $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $newIdNum = $maxNum + 1;
        do {
            $newId = 'C' . str_pad($newIdNum, 4, '0', STR_PAD_LEFT);
            $newIdNum++;
        } while ($this->customerModel->find($newId) !== null);

        $fullPhone = $countryCode . $phone;

        $data = [
            'id' => $newId,
            'name' => $name,
            'email' => $email,
            'phone' => $fullPhone,
            'status' => 'active',
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        try {
            // Simpan customer
            $this->customerModel->insert($data);
            
            // Simpan ID customer ke session
            session()->set('customer_id', $newId);
            session()->set('customer_name', $name);
            session()->set('customer_email', $email);
            
            // Redirect ke halaman schedule dengan pesan sukses
            return redirect()->to('/schedule')->with('success', 'Akun berhasil dibuat! Silahkan pilih jadwal booking Anda.');
            
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ===== METHOD UNTUK SUBMIT PILIHAN SCHEDULE =====
    public function submitSchedule()
    {
        // Ambil data dari form
        $selectedDate = $this->request->getPost('selected_date');
        $selectedTime = $this->request->getPost('selected_time');
        $customerId = session()->get('customer_id');

        // Validasi data
        if (empty($selectedDate)) {
            return redirect()->back()->with('error', 'Silakan pilih tanggal terlebih dahulu.');
        }

        if (empty($selectedTime)) {
            return redirect()->back()->with('error', 'Silakan pilih waktu sesi terlebih dahulu.');
        }

        // Simpan ke session untuk digunakan di halaman booth
        $slotId = $this->request->getPost('slot_id');
        $boothId = $this->request->getPost('booth_id');
        
        session()->set('booking_date', $selectedDate);
        session()->set('booking_time', $selectedTime);
        session()->set('slot_id', $slotId);
        session()->set('booth_id', $boothId);

        // Log data untuk debugging
        log_message('debug', 'Schedule submitted - Date: ' . $selectedDate . ', Time: ' . $selectedTime . ', Slot: ' . $slotId . ', Booth: ' . $boothId . ', Customer: ' . $customerId);

        // Redirect ke halaman pilih harga cetak dengan pesan sukses
        return redirect()->to('/print-option')->with('success', 'Jadwal berhasil dipilih! Silakan pilih harga cetak Anda.');
    }

    public function getSlots()
    {
        // ... existing code ...
    }

    public function book()
    {
        // ... existing code ...
    }
}