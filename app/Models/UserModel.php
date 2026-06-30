<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'phone', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $hidden = ['password'];

    public function getBookings($userId)
    {
        return $this->select('users.*, bookings.*, booths.name as booth_name')
                    ->join('bookings', 'bookings.user_id = users.id')
                    ->join('booths', 'booths.id = bookings.booth_id')
                    ->where('users.id', $userId)
                    ->findAll();
    }
}