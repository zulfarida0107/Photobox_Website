<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\BoothModel;

class BoothController extends BaseController
{
    protected $boothModel;

    public function __construct()
    {
        $this->boothModel = new BoothModel();
    }

    public function index()
    {
        // Ambil booth yang aktif beserta relasi lokasi
        $booths = $this->boothModel
            ->select('booths.*, locations.name AS location_name, locations.city AS location_city, locations.address AS location_address, locations.latitude, locations.longitude')
            ->join('locations', 'locations.id = booths.location_id', 'left')
            ->where('booths.status !=', 'maintenance')
            ->where('booths.status !=', 'inactive')
            ->orderBy('booths.id', 'ASC')
            ->findAll();

        $data = [
            'title'           => 'Pilih Booth - CuitBox Studio',
            'booths'          => $booths,
            'selectedBoothId' => session()->get('selected_booth_id')
        ];

        return view('User_Booking/booth', $data);
    }

    public function store()
    {
        $boothId = $this->request->getPost('booth_id');

        if (empty($boothId)) {
            return redirect()->back()->with('error', 'Silakan pilih salah satu booth terlebih dahulu.');
        }

        $booth = $this->boothModel->find($boothId);
        if (!$booth) {
            return redirect()->back()->with('error', 'Booth yang dipilih tidak valid.');
        }

        // Simpan booth terpilih ke dalam session
        session()->set([
            'selected_booth_id'    => $booth['id'],
            'selected_booth_name'  => $booth['name'],
            'selected_booth_price' => $booth['price'] ?? 0,
        ]);

        return redirect()->to('/schedule');
    }
}
