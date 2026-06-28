<?php

namespace App\Models;

use CodeIgniter\Model;

class BoothModel extends Model
{
	protected $table = 'booths';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'name',
		'ip_address',
		'latitude',
		'longitude',
		'active_booking_id',
		'status',
		'current_queue',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
