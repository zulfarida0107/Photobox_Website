<?php

namespace App\Models;

use CodeIgniter\Model;

class FrameModel extends Model
{
	protected $table = 'frames';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'name',
		'category',
		'category_id',
		'layout_id',
		'price',
		'file_path',
		'is_active',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
