<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\SessionModel;
use App\Models\PhotoModel;
use App\Services\GoogleDriveService;
use App\Services\EmailService;
use CodeIgniter\HTTP\ResponseInterface;

class SessionController extends BaseApiController
{
    private BookingModel $bookingModel;
    private SessionModel $sessionModel;
    private PhotoModel $photoModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->sessionModel = new SessionModel();
        $this->photoModel = new PhotoModel();
    }

    public function start(): ResponseInterface
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $bookingId = trim((string) ($payload['booking_id'] ?? ''));
        $frameId   = trim((string) ($payload['frame_id'] ?? ''));
        $filterId  = trim((string) ($payload['filter_id'] ?? ''));

        if ($bookingId === '' || $frameId === '' || $filterId === '') {
            return $this->failValidation('booking_id, frame_id, dan filter_id wajib diisi.');
        }

        $booking = $this->bookingModel->find($bookingId);

        if ($booking === null) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        if ($booking['status'] !== 'paid') {
            return $this->failValidation('Session hanya bisa dimulai setelah pembayaran sukses.');
        }

        $session = $this->sessionModel
            ->where('booking_id', $bookingId)
            ->first();

        if ($session === null) {

            $session = [
                'id'               => $this->makeId('ses_'),
                'booking_id'       => $bookingId,
                'frame_id'         => $frameId,
                'filter_id'        => $filterId,
                'email_recipient'  => '',
                'drive_folder_url' => '',
            ];

            $this->sessionModel->insert($session);

            $session = $this->sessionModel->find($session['id']);
        } else {

            $this->sessionModel->update($session['id'], [
                'frame_id'  => $frameId,
                'filter_id' => $filterId,
            ]);

            $session = $this->sessionModel->find($session['id']);
        }

        return $this->respondOk([
            'message' => 'Session siap. Lanjut countdown dan capture.',
            'session' => $session,
            'capture_rules' => [
                'target_shots'             => 10,
                'timer_seconds_per_shot'   => 15,
                'preview_timeout_seconds'  => 420,
            ],
        ]);
    }

    public function upload(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }
        // Hapus metadata foto lama jika user upload ulang
        $this->photoModel
            ->where('session_id', $sessionId)
            ->delete();

        // photostrip final
        $finalImage = $this->request->getFile('final_image');

        if (!$finalImage || !$finalImage->isValid()) {
            return $this->failValidation('Final image wajib dikirim.');
        }

        // seluruh hasil capture
        $capturedPhotos = $this->request->getFileMultiple('captured_photos');

        // fallback apabila FE mengirim captured_photos[]
        if (empty($capturedPhotos)) {
            $capturedPhotos = $this->request->getFileMultiple('captured_photos[]');
        }

        if (!is_array($capturedPhotos)) {
            $capturedPhotos = [];
        }

        $folder = WRITEPATH . 'uploads/sessions/' . $sessionId;

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // simpan photostrip
        $finalImage->move(
            $folder,
            'final.png',
            true
        );

        $this->photoModel->insert([
            'id'            => $this->makeId('pho_'),
            'session_id'    => $sessionId,
            'file_path'     => 'uploads/sessions/' . $sessionId . '/final.png',
            'drive_file_id' => '',
            'shot_number'   => 0,
        ]);

        // simpan video transisi jika ada
        $finalVideoTransition = $this->request->getFile('final_video_transition');
        if ($finalVideoTransition && $finalVideoTransition->isValid()) {
            $ext = $finalVideoTransition->getExtension() ?: 'mp4';
            if ($ext === 'tmp' || empty($ext)) {
                $ext = 'mp4';
            }
            $transitionFilename = 'final_video_transition.' . $ext;
            $finalVideoTransition->move(
                $folder,
                $transitionFilename,
                true
            );
            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $transitionFilename,
                'drive_file_id' => '',
                'shot_number'   => 999, // 999 for transition video
            ]);
        }

        // simpan video loop jika ada
        $finalVideoLoop = $this->request->getFile('final_video_loop');
        if ($finalVideoLoop && $finalVideoLoop->isValid()) {
            $ext = $finalVideoLoop->getExtension() ?: 'mp4';
            if ($ext === 'tmp' || empty($ext)) {
                $ext = 'mp4';
            }
            $loopFilename = 'final_video_loop.' . $ext;
            $finalVideoLoop->move(
                $folder,
                $loopFilename,
                true
            );
            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $loopFilename,
                'drive_file_id' => '',
                'shot_number'   => 998, // 998 for loop video
            ]);
        }

        $savedCaptures = [];

        foreach ($capturedPhotos as $index => $photo) {

            if (!$photo || !$photo->isValid()) {
                continue;
            }

            $filename = 'capture_' .
                ($index + 1) .
                '.' .
                $photo->getExtension();

            $photo->move(
                $folder,
                $filename,
                true
            );

            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $filename,
                'drive_file_id' => '',
                'shot_number'   => $index + 1,
            ]);

            $savedCaptures[] = $filename;
        }

        // logging untuk debugging
        log_message('debug', '=== SESSION UPLOAD ===');
        log_message('debug', 'Session : ' . $sessionId);
        log_message('debug', 'Final   : final.png');
        log_message('debug', 'Capture : ' . count($savedCaptures));

        return $this->respondOk([
            'message'         => 'Upload berhasil.',
            'session_id'      => $sessionId,
            'final_image'     => 'final.png',
            'captured_count'  => count($savedCaptures),
            'captured_files'  => $savedCaptures,
            'photos_saved'    => $this->photoModel
                ->where('session_id', $sessionId)
                ->countAllResults(),
        ]);
    }

    public function uploadLive(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        // Hapus metadata live photo lama jika user upload ulang
        $this->photoModel
            ->where('session_id', $sessionId)
            ->where('shot_number >=', 100)
            ->delete();

        // live photos (secret videos)
        $livePhotos = $this->request->getFileMultiple('live_photos');
        if (empty($livePhotos)) {
            $livePhotos = $this->request->getFileMultiple('live_photos[]');
        }
        if (!is_array($livePhotos)) {
            $livePhotos = [];
        }

        $folder = WRITEPATH . 'uploads/sessions/' . $sessionId;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $savedLivePhotos = [];
        foreach ($livePhotos as $index => $livePhoto) {
            if (!$livePhoto || !$livePhoto->isValid()) {
                continue;
            }

            $liveFilename = 'live_' .
                ($index + 1) .
                '.' .
                $livePhoto->getExtension();

            $livePhoto->move(
                $folder,
                $liveFilename,
                true
            );

            $this->photoModel->insert([
                'id'            => $this->makeId('pho_'),
                'session_id'    => $sessionId,
                'file_path'     => 'uploads/sessions/' . $sessionId . '/' . $liveFilename,
                'drive_file_id' => '',
                'shot_number'   => 100 + ($index + 1),
            ]);

            $savedLivePhotos[] = $liveFilename;
        }

        // logging untuk debugging
        log_message('debug', '=== SESSION LIVE UPLOAD ===');
        log_message('debug', 'Session : ' . $sessionId);
        log_message('debug', 'Live    : ' . count($savedLivePhotos));

        return $this->respondOk([
            'message'         => 'Upload live photo berhasil.',
            'session_id'      => $sessionId,
            'live_count'      => count($savedLivePhotos),
            'live_files'      => $savedLivePhotos,
            'photos_saved'    => $this->photoModel
                ->where('session_id', $sessionId)
                ->countAllResults(),
        ]);
    }

    public function submitEmail(string $sessionId): ResponseInterface
    {
        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $email = trim((string) ($payload['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failValidation('Format email tidak valid.');
        }

        $this->sessionModel->update($sessionId, [
            'email_recipient' => $email,
        ]);

        return $this->respondOk([
            'message' => 'Email penerima disimpan.',
            'session_id' => $sessionId,
            'email' => $email,
        ]);
    }

    public function complete(string $sessionId): ResponseInterface
    {
        set_time_limit(300);

        $session = $this->sessionModel->find($sessionId);

        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }

        if (trim((string) $session['email_recipient']) === '') {
            return $this->failValidation('Email penerima belum diisi.');
        }

        $booking = $this->bookingModel->find($session['booking_id']);

        if ($booking === null) {
            return $this->failNotFound('Booking tidak ditemukan.');
        }

        if ($booking['status'] !== 'paid') {
            return $this->failValidation('Booking belum paid.');
        }

        try {
            // Jalankan proses upload drive & kirim email di background agar respons instan
            $command = "start /B \"\" \"" . PHP_BINARY . "\" \"" . FCPATH . "../spark\" session:complete " . escapeshellarg($sessionId) . " > NUL 2>&1";
            pclose(popen($command, "r"));

            return $this->respondOk([
                'message' => 'Flow selesai (diproses di background).',
                'booking_id' => $booking['id'],
                'session_id' => $sessionId,
                'next_step' => 'reset',
            ]);
        } catch (\Throwable $e) {

            log_message('error', $e->getMessage());

            return $this->failServerError(
                $e->getMessage()
            );
        }
    }

    /**
     * Memaksa MJPEG live view stream digiCamControl (port 5514) untuk kembali
     * memproduksi frame setelah sempat berhenti akibat proses capture.
     *
     * REVISI PENTING: versi sebelumnya selalu melakukan toggle penuh
     * Hide->Show sampai 3x percobaan dengan jeda cuma 400ms -- di kamera
     * entry-level (mis. EOS 800D) lewat USB, live view engine sering butuh
     * LEBIH dari 400ms untuk mulai streaming lagi, sehingga verifikasi
     * nyaris selalu gagal di percobaan pertama dan berujung toggle
     * Hide->Show berulang (3x per jepretan). Ini menyebabkan dua masalah:
     * 1) aperture lensa fisik ikut buka-tutup ("ceklek") berkali-kali tanpa
     *    perlu, dan 2) total waktu proses bisa sampai ~5 detik, cukup lama
     *    untuk bentrok dengan jepretan berikutnya yang di-pre-trigger,
     *    menyebabkan kamera "sibuk" dan request capture baru timeout.
     *
     * Strategi baru:
     * - Cek dulu TANPA toggle sama sekali (live view kadang sudah pulih
     *   sendiri secara alami setelah capture selesai).
     * - Kalau belum pulih, baru toggle Hide->Show SEKALI (bukan diulang
     *   berkali-kali), dengan jeda tunggu yang lebih realistis.
     * - Total waktu proses dibatasi maksimal ~2.5 detik, jauh lebih singkat
     *   dari versi sebelumnya.
     *
     * @return bool true jika stream 5514 terverifikasi hidup kembali
     */
    private function rearmDslrLiveView(): bool
    {
        $cameraDriver = getenv('camera.driver') ?: 'digicamcontrol';
        if ($cameraDriver === 'python_direct') {
            // Dalam mode direct python, live view di-manage secara internal oleh driver server port 5515
            return true;
        }

        $webServerUrl = 'http://127.0.0.1:5513';

        $connection = @fsockopen('127.0.0.1', 5513, $errno, $errstr, 0.5);

        if (!$connection) {
            log_message('warning', 'rearmDslrLiveView: port 5513 tidak terjangkau.');
            return false;
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

        // 1. Cek dulu TANPA toggle apapun -- kalau live view ternyata sudah
        //    pulih sendiri (sering terjadi), selesai di sini tanpa bikin
        //    aperture kamera bergerak sama sekali.
        if ($checkFrame(0.8)) {
            log_message('info', 'rearmDslrLiveView: live view sudah aktif tanpa perlu toggle.');
            return true;
        }

        // 2. Baru kalau memang belum pulih, lakukan SATU KALI toggle penuh
        //    (Hide -> Show), bukan diulang berkali-kali, untuk meminimalkan
        //    aperture kamera "ceklek" berulang.
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Hide', 1.5);
        usleep(150000); // 150ms jeda antara Hide dan Show
        $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Show', 1.5);

        // Beri waktu tunggu yang realistis untuk hardware entry-level via USB
        usleep(1000000); // 1 detik

        if ($checkFrame(1.0)) {
            log_message('info', 'rearmDslrLiveView: berhasil setelah satu kali toggle Hide->Show.');
            return true;
        }

        log_message('error', 'rearmDslrLiveView: gagal walau sudah toggle Hide->Show. Live view 5514 kemungkinan butuh restart manual aplikasi digiCamControl.');
        return false;
    }

    private function httpGet(string $url, float $timeout = 3.0): ?string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, (int) ($timeout * 1000));
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($ch);
        if ($result === false) {
            log_message('error', 'httpGet failed: ' . curl_error($ch) . ' for ' . $url);
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        return $result;
    }

    public function captureDslr(string $sessionId): ResponseInterface
    {
        // Instrumentasi waktu -- supaya ketahuan PASTI tahap mana yang
        // paling makan waktu, bukan nebak-nebak lagi. Semua checkpoint ini
        // ditulis ke log (writable/logs/) dengan label 'DSLR_TIMING'.
        $timings = [];
        $t0 = microtime(true);
        $mark = function (string $label) use (&$timings, &$t0) {
            $now = microtime(true);
            $timings[$label] = round(($now - $t0) * 1000); // ms sejak checkpoint sebelumnya
            $t0 = $now;
        };

        $session = $this->sessionModel->find($sessionId);
        if ($session === null) {
            return $this->failNotFound('Session tidak ditemukan.');
        }
        $mark('find_session');

        // Tentukan folder temporary publik
        $tempFolder = FCPATH . 'uploads/temp/';
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0777, true);
        } else {
            // Auto cleanup file temp yang lebih tua dari 2 jam (7200 detik)
            if (rand(1, 10) === 1) {
                $cutoff = time() - 7200;
                foreach (glob($tempFolder . 'dslr_*') as $oldFile) {
                    if (is_file($oldFile) && filemtime($oldFile) < $cutoff) {
                        @unlink($oldFile);
                    }
                }
            }
        }

        $outputFile = $tempFolder . 'dslr_' . $sessionId . '_' . time() . '.jpg';
        $outputFileWin = str_replace('/', '\\', $outputFile);

        // Cek apakah web server kamera aktif berdasarkan driver di .env
        $cameraDriver = getenv('camera.driver') ?: 'digicamcontrol';
        $cameraPort = ($cameraDriver === 'python_direct') ? 5515 : 5513;
        
        $webServerUrl = 'http://127.0.0.1:' . $cameraPort;
        $isWebServerActive = false;
        
        $connection = @fsockopen('127.0.0.1', $cameraPort, $errno, $errstr, 0.5);
        if ($connection) {
            fclose($connection);
            $isWebServerActive = true;
        }
        $mark('check_webserver_active');


        if ($isWebServerActive) {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 3.0, // 3.0 detik timeout untuk transfer file/capture
                ]
            ]);

            // Dapatkan folder penyimpanan aktif dari digiCamControl API
            $sessionFolderRaw = $this->httpGet($webServerUrl . '/?slc=get&param1=session.folder&param2=', 2.0);
            $localDir = '';
            if (!empty($sessionFolderRaw) && is_dir(trim($sessionFolderRaw))) {
                $localDir = rtrim(trim($sessionFolderRaw), '\\/') . DIRECTORY_SEPARATOR;
            } else {
                $userProfile = getenv('USERPROFILE') ?: 'C:\\Users\\ZULFA';
                $possibleDirs = [
                    $userProfile . '\\OneDrive\\Pictures\\digiCamControl\\Session1\\',
                    $userProfile . '\\Pictures\\digiCamControl\\Session1\\',
                    $userProfile . '\\Pictures\\digiCamControl\\Default\\',
                    'C:\\digiCamControl\\',
                ];
                foreach ($possibleDirs as $pDir) {
                    if (is_dir($pDir)) {
                        $localDir = $pDir;
                        break;
                    }
                }
            }

            // Dapatkan nama file jepretan sebelumnya sebelum memicu shutter
            $lastCapturedUrl = $webServerUrl . '/?slc=get&param1=lastcaptured&param2=';
            $previousFilename = trim((string) $this->httpGet($lastCapturedUrl, 2.0));
            $existingFiles = [];
            if (!empty($localDir) && is_dir($localDir)) {
                $files = glob($localDir . '*.jpg');
                if ($files !== false) {
                    foreach ($files as $f) {
                        $existingFiles[basename($f)] = true;
                    }
                }
            }
            $mark('get_previous_filename');

            // 1. Pemicu jepret via Web Server API (?CMD=CaptureNoAf untuk bypass autofocus)
            $captureUrl = $webServerUrl . '/?CMD=CaptureNoAf';
            $captureResult = $this->httpGet($captureUrl, 8.0);
            $mark('trigger_shutter_command'); // waktu kirim command shutter

            // 2. Poll direktori lokal dan web API untuk mendeteksi file JPEG baru
            $filename = '';
            $maxPoll = 240; // maks 12 detik (240 * 50ms)
            $foundFilePath = '';

            for ($i = 0; $i < $maxPoll; $i++) {
                if (!empty($localDir) && is_dir($localDir)) {
                    $files = glob($localDir . '*.jpg');
                    if ($files !== false) {
                        // Periksa file terbaru
                        foreach ($files as $f) {
                            $base = basename($f);
                            if (!isset($existingFiles[$base]) && file_exists($f) && filesize($f) > 10240) {
                                usleep(80000); // Jeda 80ms agar file selesai ditulis ke disk
                                $filename = $base;
                                $foundFilePath = $f;
                                break 2;
                            }
                        }
                    }
                }

                // Cek juga lewat API lastcaptured setiap 250ms
                if ($i % 5 === 0) {
                    $currentLast = trim((string) $this->httpGet($lastCapturedUrl, 1.5));
                    if (!empty($currentLast) && $currentLast !== '-' && $currentLast !== $previousFilename) {
                        $filename = $currentLast;
                        if (!empty($localDir) && file_exists($localDir . $filename) && filesize($localDir . $filename) > 10240) {
                            $foundFilePath = $localDir . $filename;
                        }
                        break;
                    }
                }

                usleep(50000); // 50ms poll interval
            }
            $mark('poll_wait_for_camera');

            if (empty($filename)) {
                return $this->failServerError('Gagal mendeteksi file foto setelah trigger capture via Web Server. Pastikan kamera menyala dan terhubung di digiCamControl.');
            }

            // 3. Ambil file gambar (Salin langsung dari folder lokal atau download dari Web Server)
            $fileAcquired = false;

            if (!empty($foundFilePath) && file_exists($foundFilePath)) {
                $fileAcquired = @copy($foundFilePath, $outputFile);
                if ($fileAcquired) {
                    log_message('info', 'DSLR Capture: Berhasil menyalin foto secara lokal dari ' . $foundFilePath);
                }
            } elseif (!empty($localDir) && file_exists($localDir . $filename)) {
                $fileAcquired = @copy($localDir . $filename, $outputFile);
                if ($fileAcquired) {
                    log_message('info', 'DSLR Capture: Berhasil menyalin foto secara lokal dari ' . $localDir . $filename);
                }
            }

            if (!$fileAcquired) {
                // Fallback ke download dari Web Server jika penyalinan lokal gagal
                $imageUrl = $webServerUrl . '/image/' . rawurlencode($filename);
                $imageContent = $this->httpGet($imageUrl, 5.0);

                if ($imageContent !== null && !empty($imageContent)) {
                    file_put_contents($outputFile, $imageContent);
                    $fileAcquired = true;
                    log_message('info', 'DSLR Capture: Berhasil mengunduh foto dari Web Server: ' . $imageUrl);
                }
            }
            $mark('acquire_file_from_camera');

            if (!$fileAcquired) {
                return $this->failServerError('Gagal mengambil foto ' . $filename . ' baik dari folder lokal maupun via download dari Web Server.');
            }
        } else {
            // Tentukan path CameraControlCmd.exe
            $cmdPath = 'C:\\Program Files (x86)\\digiCamControl\\CameraControlCmd.exe';
            if (!file_exists($cmdPath)) {
                $cmdPath = 'C:\\Program Files\\digiCamControl\\CameraControlCmd.exe';
            }

            if (!file_exists($cmdPath)) {
                return $this->failServerError('Aplikasi digiCamControl tidak terdeteksi (Web Server mati dan CameraControlCmd.exe tidak ditemukan). Silakan jalankan digiCamControl.');
            }

            // Siapkan perintah cmd untuk digiCamControl
            $cmd = '"' . $cmdPath . '" /capture /filename "' . $outputFileWin . '"';

            // Jalankan perintah
            $output = shell_exec($cmd);
            $mark('cli_capture');

            // Verifikasi hasil file
            if (!file_exists($outputFile) || filesize($outputFile) === 0) {
                return $this->failServerError('Gagal menjepret foto dengan DSLR via CLI. Pastikan kamera menyala, terhubung via USB, dan tidak terkunci oleh aplikasi lain. Detail: ' . $output);
            }
        }

        $fileUrl = base_url('uploads/temp/' . basename($outputFile));

        // PRIORITAS KECEPATAN: rearm live view dijalankan di BACKGROUND
        // (fire-and-forget), TIDAK menunggu hasilnya sama sekali di request
        // ini. Trade-off yang disadari: beda dengan rearmDslrLiveView()
        // yang sinkron+terverifikasi (reliable tapi nambah ~3 detik),
        // pendekatan ini SELALU dispatch cepat tanpa jaminan sukses --
        // kalau live view gagal pulih, itu nggak akan ketahuan dari
        // response ini (cek writable/logs/ untuk hasil aktualnya). Dipilih
        // karena kecepatan tampil foto ke user jadi prioritas utama.
        $liveViewRearmed = null; // status tidak diverifikasi sinkron
        if ($isWebServerActive) {
            $command = 'start /B "" "' . PHP_BINARY . '" "' . FCPATH . '../spark" liveview:rearm > NUL 2>&1';
            @pclose(@popen($command, 'r'));
        }
        $mark('dispatch_liveview_rearm_async'); // seharusnya nyaris 0ms karena async

        // DI-BYPASS UNTUK KECEPATAN MAKSIMAL:
        // Karena Cuitbox berjalan secara lokal (Electron), browser dapat merender gambar asli
        // 6MB+ secara instan (hardware-accelerated). Kita bypass pembuatan pratinjau GD 
        // untuk menghemat CPU (menghilangkan jeda decode/encode ~400ms).
        $previewUrl = $fileUrl; 
        $mark('generate_preview');

        $totalMs = array_sum($timings);
        $timings['TOTAL'] = $totalMs;
        log_message('info', 'DSLR_TIMING sessionId=' . $sessionId . ' ' . json_encode($timings));

        return $this->respondOk([
            'status' => true,
            'message' => 'DSLR capture success',
            'data' => [
                'image_url' => $fileUrl,
                'preview_url' => $previewUrl,
                'filename' => basename($outputFile),
                'liveview_rearm_verified' => $liveViewRearmed,
                'timing_ms' => $timings,
            ]
        ]);
    }

    public function getTempPhoto(string $filename)
    {
        $path = FCPATH . 'uploads/temp/' . $filename;
        if (!file_exists($path)) {
            return $this->failNotFound('Photo not found');
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setBody(@file_get_contents($path));
    }

    public function startLiveView()
    {
        $webServerUrl = 'http://127.0.0.1:5513';
        log_message('info', 'startLiveView: Dipanggil.');
        
        // 1. Cek koneksi port 5513 dengan timeout cepat (0.5s) agar PHP tidak hang
        $connection = @fsockopen('127.0.0.1', 5513, $errno, $errstr, 0.5);
        if (!$connection) {
            log_message('error', 'startLiveView: Gagal konek ke port 5513 (Web Server Offline). Error: ' . $errstr . ' (' . $errno . ')');
            return $this->respondOk([
                'status' => false,
                'message' => 'digiCamControl web server is offline (port 5513). Live view cannot start.',
                'response' => false
            ]);
        }
        fclose($connection);

        // Cek dulu apakah live view sudah aktif (port 5514 bisa diakses)
        $lvConnection = @fsockopen('127.0.0.1', 5514, $lvErrno, $lvErrstr, 0.5);
        if ($lvConnection) {
            fclose($lvConnection);
            log_message('info', 'startLiveView: Live view sudah aktif di port 5514, tidak perlu toggle.');
            return $this->respondOk([
                'status' => true,
                'message' => 'Live view already active',
                'response' => 'already_active'
            ]);
        }

        // Hanya kirim Show saja (tanpa Hide dulu) untuk menghindari suara
        // ceklek dari mirror DSLR yang buka-tutup tidak perlu.
        log_message('info', 'startLiveView: Mengirim LiveViewWnd_Show...');
        $res = $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Show', 1.5);
        
        log_message('info', 'startLiveView: Live view berhasil dimulai. Response Show = "' . ($res ?? 'NULL') . '"');

        return $this->respondOk([
            'status' => true,
            'message' => 'Live view started',
            'response' => $res
        ]);
    }

    public function stopLiveView()
    {
        $webServerUrl = 'http://127.0.0.1:5513';

        // 1. Cek koneksi port 5513 dengan timeout cepat (0.5s) agar PHP tidak hang
        $connection = @fsockopen('127.0.0.1', 5513, $errno, $errstr, 0.5);
        if (!$connection) {
            return $this->respondOk([
                'status' => false,
                'message' => 'digiCamControl web server is offline (port 5513). Live view cannot stop.',
                'response' => false
            ]);
        }
        fclose($connection);

        $res = $this->httpGet($webServerUrl . '/?CMD=LiveViewWnd_Hide', 1.5);
        return $this->respondOk([
            'status' => true,
            'message' => 'Live view stopped',
            'response' => $res
        ]);
    }

    public function getSettings(): ResponseInterface
    {
        $cameraDriver = getenv('camera.driver') ?: 'digicamcontrol';
        $liveviewPort = ($cameraDriver === 'python_direct') ? 5515 : 5514;
        return $this->respondOk([
            'camera_driver' => $cameraDriver,
            'liveview_port' => $liveviewPort
        ]);
    }
}


