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
        'photo_count',
        'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}