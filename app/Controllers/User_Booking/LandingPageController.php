<?php

namespace App\Controllers\User_Booking;  // ← Namespace harus User_Booking

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\BoothModel;

class LandingPageController extends BaseController  // ← Class name
{
    protected $packageModel;
    protected $boothModel;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->boothModel = new BoothModel();
    }

    public function index()
    {
        $data = [
            'title' => 'CuitBox Studio - Studio Recording Premium',
            'packages' => $this->packageModel->findAll(),
            'booths' => $this->boothModel->findAll(),
        ];

        return view('User_Booking/landing_page', $data);
    }

    public function booking()
    {
        return redirect()->to('StatusBooking');
    }
}