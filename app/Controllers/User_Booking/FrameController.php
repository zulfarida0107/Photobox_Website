<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\FrameModel;
use App\Models\CustomerModel;

class FrameController extends BaseController
{
    protected $frameModel;
    protected $customerModel;

    public function __construct()
    {
        $this->frameModel = new FrameModel();
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $customerId = session()->get('customer_id');
        $db = \Config\Database::connect();

        // Ambil daftar kategori dari tabel frame_categories
        $categoryRows = $db->table('frame_categories')
            ->select('name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
        $categories = array_column($categoryRows, 'name');

        $selectedCategory = $this->request->getGet('category');

        // Ambil data frame yang aktif dari Admin
        $builder = $db->table('frames')
            ->select('frames.*')
            ->where('frames.is_active', 1);

        if (!empty($selectedCategory) && $selectedCategory !== 'all') {
            $builder->where('frames.category', $selectedCategory);
        }

        $frames = $builder->orderBy('frames.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title'            => 'Pilih Tema Frame - CuitBox Studio',
            'frames'           => $frames,
            'categories'       => $categories,
            'selectedCategory' => $selectedCategory,
            'customer'         => $customerId ? $this->customerModel->find($customerId) : null
        ];

        return view('User_Booking/frame', $data);
    }

    public function store()
    {
        $frameId = $this->request->getPost('frame_id');
        
        if (empty($frameId)) {
            return redirect()->back()->with('error', 'Silakan pilih tema frame terlebih dahulu.');
        }
        
        // Cek apakah frame ada di database
        $frame = $this->frameModel->find($frameId);
        if (!$frame) {
            return redirect()->back()->with('error', 'Tema frame tidak ditemukan.');
        }
        
        // Simpan pilihan frame ke session
        session()->set('selected_frame', $frameId);
        session()->set('selected_frame_name', $frame['name']);
        session()->set('selected_frame_price', $frame['price'] ?? 0);
        
        // Redirect ke halaman berikutnya
        return redirect()->to('/booking/summary')->with('success', 'Tema frame "' . $frame['name'] . '" berhasil dipilih!');
    }
}