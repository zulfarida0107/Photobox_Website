<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockedDateModel extends Model
{
    protected $table         = 'blocked_dates';
    protected $primaryKey    = 'id';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'blocked_date',
        'reason',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cek apakah suatu tanggal diblokir/libur
     */
    public function isBlocked(string $date): bool
    {
        return $this->where('blocked_date', $date)->countAllResults() > 0;
    }

    /**
     * Ambil semua tanggal libur yang akan datang (dari hari ini ke depan)
     */
    public function getUpcoming()
    {
        return $this->where('blocked_date >=', date('Y-m-d'))
                    ->orderBy('blocked_date', 'ASC')
                    ->findAll();
    }

    /**
     * Ambil semua tanggal libur terurut (terdekat / terbaru lebih dulu)
     */
    public function getAllOrdered()
    {
        return $this->orderBy('blocked_date', 'ASC')
                    ->findAll();
    }
}
