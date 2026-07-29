<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class FrameController extends BaseController
{
    public function show($filename)
    {
        $path = FCPATH . 'uploads/frames/' . $filename;

        if (!is_file($path)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Frame not found'
                ]);
        }

        $mime = mime_content_type($path);

        return $this->response
            ->setHeader('Access-Control-Allow-Origin', 'http://localhost:5173')
            ->setHeader('Access-Control-Allow-Methods', 'GET')
            ->setHeader('Access-Control-Allow-Headers', '*')
            ->setHeader('Cache-Control', 'public,max-age=3600')
            ->setContentType($mime)
            ->setBody(file_get_contents($path));
    }
}