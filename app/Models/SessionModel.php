<?php

namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
	protected $table = 'sessions';
	protected $primaryKey = 'id';
	protected $useAutoIncrement = false;
	protected $returnType = 'array';
	protected $protectFields = true;
	protected $allowedFields = [
		'id',
		'booking_id',
		'frame_id',
		'filter_id',
		'email_recipient',
		'drive_folder_url',
		'final_image_path',
		'capture_folder',
	];

	protected $useSoftDeletes = false;
	protected $useTimestamps = true;
	protected $dateFormat = 'datetime';
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
}
