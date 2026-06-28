<?php

namespace App\Controllers\Api;

use App\Models\BoothModel;
use CodeIgniter\HTTP\ResponseInterface;

class BoothApiController extends BaseApiController
{
    protected $boothModel;

    public function __construct()
    {
        $this->boothModel = new BoothModel();
    }

    /**
     * Check current active session (booking id) for a booth.
     * GET /api/booths/(:segment)/session-check
     */
    public function sessionCheck(string $id): ResponseInterface
    {
        $booth = $this->boothModel->find($id);
        if (!$booth) {
            return $this->failNotFound('Booth not found');
        }

        return $this->respondOk([
            'status' => 200,
            'booth_id' => $booth['id'],
            'active_booking_id' => $booth['active_booking_id']
        ]);
    }
}
