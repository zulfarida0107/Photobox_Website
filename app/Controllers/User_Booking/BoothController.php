<?php

namespace App\Controllers\User_Booking;

use App\Models\BoothModel;
use CodeIgniter\Controller;

class BoothController extends Controller
{
    public function index()
    {
        $boothModel = new BoothModel();
        
        // Get all booths
        $booths = $boothModel->findAll();
        
        // If no booths found, create dummy data for demo
        if (empty($booths)) {
            $booths = $this->getDummyBooths();
        }
        
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
        
        session()->set('selected_booth_id', $boothId);
        
        return redirect()->to('/booking/create')->with('success', 'Booth berhasil dipilih! Silahkan lanjutkan booking.');
    }

    private function getDummyBooths()
    {
        return [
            [
                'id' => 'B001',
                'name' => 'Booth Premium 1',
                'ip_address' => '192.168.1.101',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'active_booking_id' => null,
                'status' => 'available',
                'current_queue' => 0,
                'description' => 'Booth premium dengan kamera 4K dan lighting profesional',
                'price' => 150000,
                'capacity' => 4,
                'image' => 'premium1.jpg',
                'facilities' => ['Kamera 4K', 'Lighting Profesional', 'Background LED', 'Prop lengkap']
            ],
            [
                'id' => 'B002',
                'name' => 'Booth Premium 2',
                'ip_address' => '192.168.1.102',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'active_booking_id' => null,
                'status' => 'available',
                'current_queue' => 1,
                'description' => 'Booth premium dengan aksesoris lengkap dan ruang yang luas',
                'price' => 150000,
                'capacity' => 4,
                'image' => 'premium2.jpg',
                'facilities' => ['Kamera 4K', 'Lighting Profesional', 'Background Video', 'Prop lengkap', 'Monitor 32"']
            ],
            [
                'id' => 'B003',
                'name' => 'Booth Standar 1',
                'ip_address' => '192.168.1.103',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'active_booking_id' => null,
                'status' => 'available',
                'current_queue' => 0,
                'description' => 'Booth standar dengan kualitas gambar yang baik dan nyaman',
                'price' => 100000,
                'capacity' => 3,
                'image' => 'standar1.jpg',
                'facilities' => ['Kamera HD', 'Lighting Standar', 'Background Polos', 'Prop dasar']
            ],
            [
                'id' => 'B004',
                'name' => 'Booth Standar 2',
                'ip_address' => '192.168.1.104',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'active_booking_id' => null,
                'status' => 'available',
                'current_queue' => 2,
                'description' => 'Booth standar dengan desain minimalis dan modern',
                'price' => 100000,
                'capacity' => 3,
                'image' => 'standar2.jpg',
                'facilities' => ['Kamera HD', 'Lighting Standar', 'Background Polos', 'Aksesoris tambahan']
            ],
            [
                'id' => 'B005',
                'name' => 'Booth Family 1',
                'ip_address' => '192.168.1.105',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'active_booking_id' => null,
                'status' => 'available',
                'current_queue' => 0,
                'description' => 'Booth khusus keluarga dengan ruang yang luas dan nyaman',
                'price' => 200000,
                'capacity' => 6,
                'image' => 'family1.jpg',
                'facilities' => ['Kamera 4K', 'Lighting Premium', 'Background LED', 'Prop lengkap', 'Ruang luas']
            ],
            [
                'id' => 'B006',
                'name' => 'Booth Couple 1',
                'ip_address' => '192.168.1.106',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'active_booking_id' => null,
                'status' => 'available',
                'current_queue' => 1,
                'description' => 'Booth romantis khusus untuk couple dengan dekorasi spesial',
                'price' => 180000,
                'capacity' => 2,
                'image' => 'couple1.jpg',
                'facilities' => ['Kamera 4K', 'Lighting Romantis', 'Background Dekorasi', 'Prop couple']
            ],
        ];
    }
}