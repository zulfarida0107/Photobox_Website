<?php

namespace App\Models;

use CodeIgniter\Model;

class BoothModel extends Model
{
    protected $table = 'booths';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'features', 'image_url', 'price', 'is_active'];
    protected $useTimestamps = true;

    public function getActiveBooths()
    {
        return $this->where('is_active', 1)->findAll();
    }

    public function getBoothWithAvailability($boothId, $date)
    {
        return $this->select('booths.*, COUNT(bookings.id) as booked_count')
                    ->join('bookings', 'bookings.booth_id = booths.id AND bookings.booking_date = "' . $date . '"', 'left')
                    ->where('booths.id', $boothId)
                    ->first();
    }
}