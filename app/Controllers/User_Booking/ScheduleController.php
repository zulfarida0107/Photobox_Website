<?php

namespace App\Controllers\User_Booking;  // ← Perbaiki namespace

use App\Controllers\BaseController;
use App\Models\SlotModel;
use App\Models\BoothModel;

class ScheduleController extends BaseController
{
    protected $slotModel;
    protected $boothModel;

    public function __construct()
    {
        $this->slotModel = new SlotModel();
        $this->boothModel = new BoothModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Schedule Booking - CuitBox Studio',
            'booths' => $this->boothModel->findAll(),
        ];

        return view('User_Booking/schedule', $data);
    }

    public function getSlots()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $boothId = $this->request->getGet('booth_id');

        $query = $this->slotModel;

        if ($start && $end) {
            $query->where('slot_date >=', $start);
            $query->where('slot_date <=', $end);
        }

        if ($boothId) {
            $query->where('booth_id', $boothId);
        }

        $slots = $query->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $slots
        ]);
    }

    public function book()
    {
        $rules = [
            'slot_id' => 'required|numeric',
            'customer_name' => 'required|min_length[3]',
            'customer_email' => 'required|valid_email',
            'customer_phone' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $slotId = $this->request->getPost('slot_id');
        $slot = $this->slotModel->find($slotId);

        if (!$slot) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Slot tidak ditemukan'
            ]);
        }

        // Cek ketersediaan
        if ($slot['booked_count'] >= $slot['max_bookings']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Slot sudah penuh'
            ]);
        }

        // Update booked_count
        $newCount = $slot['booked_count'] + 1;
        $newStatus = ($newCount >= $slot['max_bookings']) ? 'full' : 'available';

        $this->slotModel->update($slotId, [
            'booked_count' => $newCount,
            'status' => $newStatus
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Booking berhasil!',
            'data' => [
                'slot_id' => $slotId,
                'date' => $slot['slot_date'],
                'time' => $slot['start_time'] . ' - ' . $slot['end_time']
            ]
        ]);
    }
}