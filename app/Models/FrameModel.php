<?php

namespace App\Models;

use CodeIgniter\Model;

class FrameModel extends Model
{
<<<<<<< HEAD
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
=======
	protected $table = 'frames';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'name',
		'category_id',
		'layout_id',
		'file_path',
		'is_active',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
>>>>>>> 809e25cbdcf9b46ae7d84d91ea5cae63ea09a3e1
}
