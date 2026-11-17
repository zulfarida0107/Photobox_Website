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
        $serviceAccountPath = WRITEPATH . 'google/credentials.json';
        if (file_exists($serviceAccountPath)) {
            $client = new Client();
            $client->setAuthConfig($serviceAccountPath);
            $client->setScopes([Drive::DRIVE]);
            $this->drive = new Drive($client);
            $this->photoModel = new PhotoModel();
            $this->sessionModel = new SessionModel();
            return;
        }

        $config = config(\Config\GoogleDrive::class);
        $client = new Client();
        $client->setAuthConfig($config->oauthCredentials);
        $client->setScopes([Drive::DRIVE]);
        $client->setAccessType('offline');

        $tokenPath = WRITEPATH . 'google/token.json';
        if (! file_exists($tokenPath)) {
            throw new \RuntimeException('Credentials Google Drive tidak ditemukan.');
        }

        $token = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken() ?: ($token['refresh_token'] ?? null);
            if (!$refreshToken) {
                throw new \RuntimeException('Refresh token tidak tersedia.');
            }
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            $mergedToken = array_merge($token, $client->getAccessToken() ?: []);
            if (!empty($refreshToken) && empty($mergedToken['refresh_token'])) {
                $mergedToken['refresh_token'] = $refreshToken;
            }
            file_put_contents($tokenPath, json_encode($mergedToken, JSON_PRETTY_PRINT));
        }

        $this->drive = new Drive($client);
        $this->photoModel = new PhotoModel();
        $this->sessionModel = new SessionModel();
    }

    public function createFolder(string $folderName): string
    {
        $config = config(\Config\GoogleDrive::class);
        $params = [
            'name'     => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
        ];
        if (!empty($config->folderId)) {
            $params['parents'] = [$config->folderId];
        }

        $folder = new DriveFile($params);

        try {
            $createdFolder = $this->drive->files->create($folder, ['fields' => 'id']);
            return $createdFolder->id;
        } catch (\Throwable $e) {
            if (!empty($params['parents'])) {
                unset($params['parents']);
                $folder = new DriveFile($params);
                $createdFolder = $this->drive->files->create($folder, ['fields' => 'id']);
                return $createdFolder->id;
            }
            throw $e;
        }
    }

    public function uploadFile(string $folderId, string $localPath, string $fileName): string
    {
        if (!file_exists($localPath)) {
            if (file_exists(FCPATH . $localPath)) {
                $localPath = FCPATH . $localPath;
            } elseif (file_exists(WRITEPATH . $localPath)) {
                $localPath = WRITEPATH . $localPath;
            } else {
                throw new \RuntimeException("File tidak ditemukan: {$localPath}");
            }
        }

        $mime = mime_content_type($localPath);
        if (!$mime || $mime === 'application/octet-stream') {
            if (str_ends_with(strtolower($fileName), '.mp4')) {
                $mime = 'video/mp4';
            } elseif (str_ends_with(strtolower($fileName), '.webm')) {
                $mime = 'video/webm';
            } elseif (str_ends_with(strtolower($fileName), '.png')) {
                $mime = 'image/png';
            } else {
                $mime = 'image/jpeg';
            }
        }

        $metadata = new DriveFile([
            'name'     => $fileName,
            'parents'  => [$folderId],
            'mimeType' => $mime,
        ]);

        $file = $this->drive->files->create(
            $metadata,
            [
                'data'       => file_get_contents($localPath),
                'mimeType'   => $mime,
                'uploadType' => 'multipart',
                'fields'     => 'id',
            ]
        );

        return (string) (is_object($file) ? ($file->id ?? '') : '');
    }

    public function uploadSessionPhotos(string $sessionId): string
    {
        $session = $this->sessionModel->find($sessionId);
        if (!$session) {
            throw new \RuntimeException('Session tidak ditemukan.');
        }

        $folderId = $this->createSessionFolder("CuitBox - Sesi {$sessionId}");

        $sessionDir = WRITEPATH . "uploads/sessions/{$sessionId}/";
        if (!is_dir($sessionDir)) {
            $sessionDir = FCPATH . "uploads/sessions/{$sessionId}/";
        }

        if (is_dir($sessionDir)) {
            $files = glob($sessionDir . '*.*') ?: [];
            foreach ($files as $filePath) {
                if (is_file($filePath)) {
                    try {
                        $this->uploadFile(
                            $folderId,
                            $filePath,
                            basename($filePath)
                        );
                    } catch (\Throwable $e) {
                        log_message('warning', 'Upload file to drive error: ' . $filePath . ' | ' . $e->getMessage());
                    }
                }
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

        $this->drive->permissions->create($folderId, $permission);
    }

    public function getFolderLink(string $folderId): string
    {
        return "https://drive.google.com/drive/folders/{$folderId}";
    }
}