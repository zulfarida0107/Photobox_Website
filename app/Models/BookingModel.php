<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
	protected $table = 'bookings';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'booking_code',
		'customer_id',
		'slot_id',
		'booth_id',
		'package_id',
		'queue_number',
		'status',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
