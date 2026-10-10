<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\GoogleDriveService;

class TestDrive extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'drive:test';
    protected $description = 'Test Google Drive upload';

    public function run(array $params)
    {
        CLI::write("Testing Google Drive upload with Service Account...", "yellow");
        try {
            $drive = new GoogleDriveService();
            $config = config(\Config\GoogleDrive::class);
            CLI::write("Configured Parent Folder ID: " . $config->folderId, "cyan");

            $folderId = $drive->createFolder("Test CuitBox Folder " . date('Y-m-d H:i:s'));
            CLI::write("Created Subfolder ID: " . $folderId, "green");

            $testFile = WRITEPATH . 'drive_test.txt';
            file_put_contents($testFile, "Photobox Google Drive integration test at " . date('Y-m-d H:i:s'));

            $fileId = $drive->uploadFile($folderId, $testFile, 'drive_test.txt');
            CLI::write("Uploaded Test File ID: " . $fileId, "green");

            $drive->makeFolderPublic($folderId);
            $link = $drive->getFolderLink($folderId);
            CLI::write("Folder Public Link: " . $link, "green");
            CLI::write("SUCCESS: ALL GOOGLE DRIVE UPLOAD TESTS PASSED!", "green");
        } catch (\Throwable $e) {
            CLI::error("ERROR: " . $e->getMessage());
        }
    }
}
