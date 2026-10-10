<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
	protected $table = 'customers';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'name',
		'email',
		'password',
		'phone',
		'status',
		'reset_token',
		'reset_token_expires_at',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
