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
        
        // Ambil data frame yang aktif
        $frames = $this->frameModel->where('is_active', 1)->findAll();
        
        $data = [
            'title' => 'Pilih Tema Frame - CuitBox Studio',
            'frames' => $frames,
            'customer' => $customerId ? $this->customerModel->find($customerId) : null
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
        
        // Redirect ke halaman berikutnya
        return redirect()->to('/user-payment')->with('success', 'Tema frame "' . $frame['name'] . '" berhasil dipilih!');
    }
}