<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
	protected $table = 'payments';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'booking_id',
		'print_option_id',
		'amount',
		'gateway_ref',
		'status',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
