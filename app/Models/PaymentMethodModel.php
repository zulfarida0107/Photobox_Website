<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 
        'type', 
        'description', 
        'created_at', 
        'updated_at'
    ];
    protected $useTimestamps = true; // Auto manage created_at & updated_at
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';
    
    // Optional: method untuk mengambil berdasarkan type
    public function getByType($type)
    {
        return $this->where('type', $type)->findAll();
    }
    
    // Optional: method untuk mengambil bank saja
    public function getBanks()
    {
        return $this->where('type', 'bank')->findAll();
    }
    
    // Optional: method untuk mengambil e-wallet saja
    public function getEwallets()
    {
        return $this->where('type', 'e-wallet')->findAll();
    }
}