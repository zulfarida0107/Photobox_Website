<?php

namespace App\Controllers\Api;

use App\Models\PhotoModel;
use App\Models\SessionModel;
use CodeIgniter\HTTP\ResponseInterface;

class PhotoController extends BaseApiController
{
    private SessionModel $sessionModel;
    private PhotoModel $photoModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
        $this->photoModel = new PhotoModel();
    }

    public function upload(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);
        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        $files = $this->request->getFiles();
        $photoFiles = $files['photos'] ?? null;

        if ($photoFiles === null) {
            return $this->failValidation('File photos wajib diunggah.');
        }

        if (! is_array($photoFiles)) {
            $photoFiles = [$photoFiles];
        }

        $uploadDir = FCPATH . 'uploads/sessions/' . $sessionId;
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $saved = [];
        $nextShot = (int) $this->photoModel->where('session_id', $sessionId)->countAllResults() + 1;

        foreach ($photoFiles as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $randomName = $file->getRandomName();
            $file->move($uploadDir, $randomName);

            $photo = [
                'id' => $this->makeId('pho_'),
                'session_id' => $sessionId,
                'file_path' => 'uploads/sessions/' . $sessionId . '/' . $randomName,
                'drive_file_id' => null,
                'shot_number' => $nextShot,
            ];

            $this->photoModel->insert($photo);
            $saved[] = $photo;
            $nextShot++;
        }

        return $this->respondCreated([
            'message' => 'Foto berhasil diunggah.',
            'photos' => $saved,
            'total_photos' => $this->photoModel->where('session_id', $sessionId)->countAllResults(),
        ]);
    }
}
