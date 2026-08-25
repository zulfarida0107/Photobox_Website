<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table            = 'locations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'name',
        'city',
        'address',
        'latitude',
        'longitude',
        'status',
    ];

    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getActiveLocations()
    {
        return $this->where('status', 'active')->orderBy('name', 'ASC')->findAll();
    }
}
