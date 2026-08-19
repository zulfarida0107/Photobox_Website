<?php

namespace App\Models;

use CodeIgniter\Model;

class FrameModel extends Model
{
    protected $table = 'frames';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'name',
        'category_id',
        'file_path',
        'Price', 
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
