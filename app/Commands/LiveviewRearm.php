<?php
/**
 * PENTING: File ini HARUS diletakkan di folder app/Commands/LiveviewRearm.php
 * (bukan di folder Controllers/Api), mengikuti pola yang sama seperti command
 * "session:complete" yang sudah ada di project kamu (dipanggil dari
 * SessionController::complete()).
 *
 * Cara register: CodeIgniter 4 otomatis mendeteksi command baru di
 * app/Commands/ tanpa perlu registrasi manual tambahan, selama namespace
 * dan lokasi file sudah benar.
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
        // Tunggu 2 detik agar digiCamControl selesai memproses jepretan & transfer USB secara penuh
        usleep(2000000);

        $webServerUrl = 'http://127.0.0.1:5513';

        // Pastikan webserver digiCamControl aktif
        $connection = @fsockopen('127.0.0.1', 5513, $errno, $errstr, 0.5);
        if (!$connection) {
            CLI::write('liveview:rearm - port 5513 tidak terjangkau, dibatalkan.', 'red');
            return;
        }
        fclose($connection);

        $checkFrame = function (float $timeoutSeconds): bool {
            $verifyConnection = @fsockopen('127.0.0.1', 5514, $verrno, $verrstr, 0.5);
            if (!$verifyConnection) {
                return false;
            }
            fclose($verifyConnection);

            $verifyCtx = stream_context_create(['http' => ['timeout' => $timeoutSeconds]]);
            $frame = @file_get_contents('http://127.0.0.1:5514/live', false, $verifyCtx);

            return $frame !== false && strlen($frame) > 500 && strpos($frame, "\xFF\xD8") !== false;
        };

        // 1. Cek dulu TANPA toggle
        if ($checkFrame(0.8)) {
            CLI::write('liveview:rearm - live view sudah aktif tanpa perlu toggle.', 'green');
            log_message('info', 'liveview:rearm - live view sudah aktif tanpa perlu toggle.');
            return;
        }

        // 2. Jika belum aktif, lakukan toggle Hide -> Show SEKALI
        CLI::write('liveview:rearm - live view mati. Melakukan toggle...', 'yellow');
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Hide', 1.5);
        usleep(150000); // 150ms jeda
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Show', 1.5);

        // Tunggu 1.2 detik agar kamera entry-level menginisialisasi port 5514 stream via USB
        usleep(1200000);

        if ($checkFrame(1.0)) {
            CLI::write('liveview:rearm - berhasil pulih setelah toggle.', 'green');
            log_message('info', 'liveview:rearm - berhasil pulih setelah toggle.');
            return;
        }

        // 3. Jika masih gagal, tunggu sedikit lagi dan coba sekali lagi toggle
        CLI::write('liveview:rearm - belum pulih. Mencoba sekali lagi...', 'yellow');
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Hide', 1.5);
        usleep(150000);
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Show', 1.5);

        usleep(1500000); // Tunggu 1.5 detik

        if ($checkFrame(1.2)) {
            CLI::write('liveview:rearm - berhasil pulih pada percobaan kedua.', 'green');
            log_message('info', 'liveview:rearm - berhasil pulih pada percobaan kedua.');
            return;
        }

        CLI::write('liveview:rearm - gagal memulihkan live view.', 'red');
        log_message('error', 'liveview:rearm - gagal memulihkan live view setelah dua kali coba.');
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