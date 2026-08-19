<?php
/**
 * PENTING: File ini HARUS diletakkan di folder app/Commands/LiveviewRearm.php
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class LiveviewRearm extends BaseCommand
{
    protected $group       = 'Booth';
    protected $name        = 'liveview:rearm';
    protected $description = 'Memaksa live view stream digiCamControl (port 5514) untuk kembali memproduksi frame setelah capture foto.';

    public function run(array $params)
    {
        // Tunggu 1.5 detik agar digiCamControl selesai memproses jepretan & transfer USB secara penuh
        usleep(1500000);

        $webServerUrl = 'http://127.0.0.1:5513';

        // Pastikan webserver digiCamControl aktif
        $connection = @fsockopen('127.0.0.1', 5513, $errno, $errstr, 0.5);
        if (!$connection) {
            CLI::write('liveview:rearm - port 5513 tidak terjangkau, dibatalkan.', 'red');
            return;
        }
        fclose($connection);

        $checkFrame = function (float $timeoutSeconds): bool {
            $socket = @fsockopen('127.0.0.1', 5514, $errno, $errstr, 0.5);
            if (!$socket) {
                return false;
            }
            
            $seconds = floor($timeoutSeconds);
            $microseconds = ($timeoutSeconds - $seconds) * 1000000;
            stream_set_timeout($socket, (int)$seconds, (int)$microseconds);
            
            $request = "GET /live HTTP/1.1\r\nHost: 127.0.0.1:5514\r\nConnection: close\r\n\r\n";
            fwrite($socket, $request);
            
            $data = '';
            $startTime = microtime(true);
            while (!feof($socket) && strlen($data) < 8192) {
                $chunk = fread($socket, 1024);
                if ($chunk === false || $chunk === '') {
                    break;
                }
                $data .= $chunk;
                
                if ((microtime(true) - $startTime) > $timeoutSeconds) {
                    break;
                }
            }
            fclose($socket);
            
            return strlen($data) > 500 && strpos($data, "\xFF\xD8") !== false;
        };

        // 1. Cek dulu TANPA toggle
        if ($checkFrame(0.5)) {
            CLI::write('liveview:rearm - live view sudah aktif tanpa perlu toggle.', 'green');
            log_message('info', 'liveview:rearm - live view sudah aktif tanpa perlu toggle.');
            return;
        }

        // 2. Jika belum aktif, lakukan reset penuh Hide -> Show dengan jeda 1.5 detik
        CLI::write('liveview:rearm - live view mati. Melakukan reset (Hide -> Show)...', 'yellow');
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Hide', 1.5);
        
        // Jeda 1.5 detik agar digiCamControl menutup window dan membebaskan port
        usleep(1500000); 
        
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Show', 1.5);

        // Lakukan polling selama 5 detik (10 * 500ms) untuk memberi waktu kamera membuka cermin
        for ($i = 1; $i <= 10; $i++) {
            usleep(500000); // 500ms
            if ($checkFrame(0.5)) {
                CLI::write("liveview:rearm - berhasil pulih setelah reset (percobaan ke-$i).", 'green');
                log_message('info', "liveview:rearm - berhasil pulih setelah reset (percobaan ke-$i).");
                return;
            }
        }

        CLI::write('liveview:rearm - gagal memulihkan live view setelah semua percobaan.', 'red');
        log_message('error', 'liveview:rearm - gagal memulihkan live view setelah semua percobaan.');
    }

    private function httpGet(string $url, float $timeout = 3.0): ?string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, (int) ($timeout * 1000));
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}