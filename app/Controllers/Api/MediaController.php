<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class MediaController extends BaseController
{
    public function showSessionFile(...$segments)
    {
        $relativePath = implode('/', $segments);
        $fullPath = WRITEPATH . 'uploads/sessions/' . $relativePath;

        if (!is_file($fullPath)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status'  => false,
                    'message' => 'File not found: ' . $relativePath,
                ]);
        }

        $mime = mime_content_type($fullPath);
        if (!$mime) {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'png'  => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'webm' => 'video/webm',
                'mp4'  => 'video/mp4',
                default => 'application/octet-stream',
            };
        }

        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setContentType($mime)
            ->setBody(file_get_contents($fullPath));
    }
}
