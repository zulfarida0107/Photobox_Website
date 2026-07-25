<?php

namespace App\Models;

use CodeIgniter\Model;

class FrameLayoutModel extends Model
{
    protected $table = 'frame_layouts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $protectFields = true;
    protected $allowedFields = [
        'id',
        'name',
        'photo_count',
        'aspect_ratio',
        'slot_coordinates',
        'image_path',
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
