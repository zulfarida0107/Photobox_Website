<?php

namespace App\Controllers\User_Booking;

use App\Models\BoothModel;
use App\Models\BookingModel;
use App\Models\CustomerModel;
use CodeIgniter\Controller;

class BoothController extends Controller
{
    protected $boothModel;
    protected $bookingModel;
    protected $customerModel;
    protected $session;

    public function __construct()
    {
        $this->boothModel = new BoothModel();
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $boothModel = new BoothModel();
        
        // Get all booths
        $booths = $boothModel->findAll();
        
        $data = [
            'title' => 'Pilih Booth - CuitBox Studio',
            'booths' => $booths,
        ];
        
        return view('User_Booking/booth', $data);
    }

   
    public function store()
    {
        $rules = [
            'booth_id' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $boothId = $this->request->getPost('booth_id');
        
        // Simpan ke session
        session()->set('selected_booth_id', $boothId);
        
        // Redirect ke halaman payment dengan parameter booth_id
        return redirect()->to('/payment?booth_id=' . $boothId)->with('success', 'Booth berhasil dipilih! Silahkan lanjutkan ke pembayaran.');
    }
}