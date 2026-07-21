<?php

namespace App\Controllers;

use App\Services\GoogleDriveService;

class GoogleDriveController extends BaseController
{

    public function upload($sessionId)
    {
        $drive = new GoogleDriveService();

        $link = $drive->uploadSessionPhotos($sessionId);

        return $this->response->setJSON([
            'success' => true,
            'link' => $link
        ]);
    }
}
