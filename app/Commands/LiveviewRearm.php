<?php
/**
 * File: app/Commands/LiveviewRearm.php
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class LiveviewRearm extends BaseCommand
{
    protected $group       = 'Booth';
    protected $name        = 'liveview:rearm';
    protected $description = 'Pemeriksaan pasif status live view digiCamControl tanpa memicu buka-tutup GUI window.';

    public function run(array $params)
    {
        // Pemeriksaan pasif (tidak mengirim LiveViewWnd_Hide / Show agar tidak memunculkan popup di desktop)
        $connection = @fsockopen('127.0.0.1', 5513, $errno, $errstr, 0.5);
        if (!$connection) {
            CLI::write('liveview:rearm - port 5513 offline.', 'yellow');
            return;
        }
        fclose($connection);

        CLI::write('liveview:rearm - port 5513 aktif, live view berjalan normal.', 'green');
    }
}
