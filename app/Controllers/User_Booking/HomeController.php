<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BoothModel;
use App\Models\FrameModel;
use App\Models\PrintOptionModel;

class HomeController extends BaseController
{
    public function index()
    {
        $boothModel       = new BoothModel();
        $frameModel       = new FrameModel();
        $printOptionModel = new PrintOptionModel();

        $booths       = [];
        $frames       = [];
        $printOptions = [];

        try {
            $booths = $boothModel->findAll();
        } catch (\Throwable $e) {
            log_message('warning', 'Could not load booths: ' . $e->getMessage());
        }

        try {
            $frames = $frameModel->where('is_active', 1)->findAll(8);
            if (empty($frames)) {
                $frames = $frameModel->findAll(8);
            }
        } catch (\Throwable $e) {
            log_message('warning', 'Could not load frames: ' . $e->getMessage());
        }

        try {
            $printOptions = $printOptionModel->orderBy('copies', 'ASC')->findAll();
        } catch (\Throwable $e) {
            log_message('warning', 'Could not load print options: ' . $e->getMessage());
        }

        $data = [
            'title'        => 'CuitBox Studio - Premium Photobox Experience',
            'booths'       => $booths,
            'frames'       => $frames,
            'printOptions' => $printOptions,
        ];

        return view('User_Booking/index', $data);
    }

    public function booking()
    {
        // Jika sudah login, langsung ke alur pemilihan booth
        if (session()->get('isUserLoggedIn') || session()->get('isLoggedIn')) {
            return redirect()->to(base_url('booth'));
        }

        // Jika belum login, simpan flash message dan arahkan ke pendaftaran
        session()->setFlashdata('info', 'Silakan lengkapi pendaftaran untuk memulai alur booking.');
        return redirect()->to(base_url('register'));
    }
}
