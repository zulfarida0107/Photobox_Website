<?php

namespace App\Models;

use CodeIgniter\Model;

class PhotoModel extends Model
{
	protected $table = 'photos';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'session_id',
		'file_path',
		'drive_file_id',
		'shot_number',
	];

	protected bool $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
