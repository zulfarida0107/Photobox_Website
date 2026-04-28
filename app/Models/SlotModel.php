<?php

namespace App\Models;

use CodeIgniter\Model;

class SlotModel extends Model
{
	protected $table = 'slots';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'booth_id',
		'slot_date',
		'start_time',
		'max_bookings',
		'booked_count',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
