<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
	protected $table = 'packages';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'name',
		'photo_count',
		'duration',
		'base_price',
	];

	protected bool $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
