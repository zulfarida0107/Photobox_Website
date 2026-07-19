<?php

namespace App\Services;

use App\Models\PhotoModel;
use App\Models\SessionModel;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    protected Drive $drive;

    private PhotoModel $photoModel;
    private SessionModel $sessionModel;

    public function __construct()
    {
        $config = config(\Config\GoogleDrive::class);

        $client = new Client();

        // OAuth credentials
        $client->setAuthConfig(
            $config->oauthCredentials
        );

        $client->setScopes([
            Drive::DRIVE
        ]);

        $client->setAccessType('offline');

        $tokenPath = WRITEPATH . 'google/token.json';

        if (! file_exists($tokenPath)) {
            throw new \RuntimeException(
                'token.json tidak ditemukan. Jalankan OAuth terlebih dahulu.'
            );
        }

        $token = json_decode(
            file_get_contents($tokenPath),
            true
        );

        $client->setAccessToken($token);

        // Refresh access token otomatis
        if ($client->isAccessTokenExpired()) {

            if (! $client->getRefreshToken()) {
                throw new \RuntimeException(
                    'Refresh token tidak tersedia. Jalankan OAuth kembali.'
                );
            }

            $client->fetchAccessTokenWithRefreshToken(
                $client->getRefreshToken()
            );

            file_put_contents(
                $tokenPath,
                json_encode(
                    $client->getAccessToken(),
                    JSON_PRETTY_PRINT
                )
            );
        }

        $this->drive = new Drive($client);

        $this->photoModel = new PhotoModel();
        $this->sessionModel = new SessionModel();
    }

    public function createFolder(string $folderName): string
    {
        $config = config(\Config\GoogleDrive::class);

        $folder = new DriveFile([
            'name'     => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents'  => [
                $config->folderId
            ]
        ]);

        $createdFolder = $this->drive->files->create(
            $folder,
            [
                'fields' => 'id'
            ]
        );

        return $createdFolder->id;
    }

    public function uploadFile(
        string $folderId,
        string $localPath,
        string $fileName
    ): string {

        if (! file_exists($localPath)) {
            throw new \RuntimeException(
                "File tidak ditemukan: {$localPath}"
            );
        }

        $metadata = new DriveFile([
            'name'    => $fileName,
            'parents' => [$folderId],
        ]);

        $file = $this->drive->files->create(
            $metadata,
            [
                'data'       => file_get_contents($localPath),
                'mimeType'   => mime_content_type($localPath),
                'uploadType' => 'multipart',
                'fields'     => 'id',
            ]
        );

        return $file->id;
    }

    public function uploadSessionPhotos(string $sessionId): string
    {
        $session = $this->sessionModel->find($sessionId);

        if (! $session) {
            throw new \RuntimeException(
                'Session tidak ditemukan.'
            );
        }

        $photos = $this->photoModel
            ->where('session_id', $sessionId)
            ->orderBy('shot_number', 'ASC')
            ->findAll();

        if (empty($photos)) {
            throw new \RuntimeException(
                'Foto tidak ditemukan.'
            );
        }

        $folderId = $this->createSessionFolder(
            $sessionId
        );

        foreach ($photos as $photo) {

            $localPath = WRITEPATH . $photo['file_path'];

            try {

                $fileId = $this->uploadFile(
                    $folderId,
                    $localPath,
                    basename($localPath)
                );

                $this->photoModel->update(
                    $photo['id'],
                    [
                        'drive_file_id' => $fileId
                    ]
                );
            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'Upload Google Drive gagal: ' .
                    $localPath .
                    ' | ' .
                    $e->getMessage()
                );

                throw $e;
            }
        }

        $this->makeFolderPublic($folderId);

        return $this->getFolderLink($folderId);
    }

    public function createSessionFolder(string $sessionCode): string
    {
        return $this->createFolder($sessionCode);
    }

    public function makeFolderPublic(string $folderId): void
    {
        $permission = new Permission([
            'type' => 'anyone',
            'role' => 'reader',
            'allowFileDiscovery' => false,
        ]);

        $this->drive->permissions->create(
            $folderId,
            $permission
        );
    }

    public function getFolderLink(string $folderId): string
    {
        return "https://drive.google.com/drive/folders/{$folderId}";
    }
}