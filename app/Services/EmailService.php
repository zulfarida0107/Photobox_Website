<?php

namespace App\Services;

class EmailService
{
    protected $email;

    public function __construct()
    {
        // CI4 cara yang benar: kirim config array ke Services::email()
        // parameter kedua true = fresh instance (tidak share singleton)
        $config = new \Config\Email();
        $config->protocol   = env('email.protocol',   'smtp');
        $config->SMTPHost   = env('email.SMTPHost',   'smtp.gmail.com');
        $config->SMTPUser   = env('email.SMTPUser',   '');
        $config->SMTPPass   = env('email.SMTPPass',   '');
        $config->SMTPPort   = (int) env('email.SMTPPort', 587);
        $config->SMTPCrypto = env('email.SMTPCrypto', 'tls');
        $config->mailType   = 'html';
        $config->charset    = 'UTF-8';
        $config->fromEmail  = env('email.fromEmail',  env('email.SMTPUser', ''));
        $config->fromName   = env('email.fromName',   'PhotoBox Studio');

        $this->email = \Config\Services::email($config, true);
    }

    public function sendDriveLink(
        string $recipient,
        ?string $folderLink = null,
        ?string $sessionId = null
    ): bool {

        // Perpanjang batas waktu eksekusi untuk pengiriman file besar via SMTP
        set_time_limit(300);

        $this->email->clear(true); // true = clear termasuk attachment

        $this->email->setFrom(
            env('email.fromEmail', env('email.SMTPUser', '')),
            'CuitBox Studio'
        );

        $this->email->setTo($recipient);
        $this->email->setSubject('CuitBox Studio - Foto Sesi Anda Siap! 📸');
        $this->email->setMailType('html');

        $attachedCount = 0;
        $totalAttachSize = 0;
        $MAX_TOTAL_SIZE = 24 * 1024 * 1024; // 24MB batas total lampiran

        // Attach foto & video dari folder sesi jika sessionId diberikan
        if (!empty($sessionId)) {
            $sessionDir = WRITEPATH . 'uploads/sessions/' . $sessionId;
            if (is_dir($sessionDir)) {

                // File 1: final photostrip (wajib)
                $finalImage = $sessionDir . '/final.png';
                if (file_exists($finalImage)) {
                    $sz = filesize($finalImage);
                    $this->email->attach($finalImage, 'attachment', 'CuitBox_Photostrip_Final.png');
                    $attachedCount++;
                    $totalAttachSize += $sz;
                }

                // File 2: video transisi (live photo - prioritas tinggi)
                $transitionFiles = glob($sessionDir . '/final_video_transition.*');
                if (!empty($transitionFiles)) {
                    $vPath = $transitionFiles[0];
                    $vSize = filesize($vPath);
                    if (($totalAttachSize + $vSize) < $MAX_TOTAL_SIZE) {
                        $ext = pathinfo($vPath, PATHINFO_EXTENSION);
                        $this->email->attach($vPath, 'attachment', 'CuitBox_LivePhoto_Transition.' . $ext);
                        $attachedCount++;
                        $totalAttachSize += $vSize;
                    }
                }

                // File 3: video loop (live photo loop — INI YANG SEBELUMNYA HILANG!)
                $loopFiles = glob($sessionDir . '/final_video_loop.*');
                if (!empty($loopFiles)) {
                    $lPath = $loopFiles[0];
                    $lSize = filesize($lPath);
                    if (($totalAttachSize + $lSize) < $MAX_TOTAL_SIZE) {
                        $ext = pathinfo($lPath, PATHINFO_EXTENSION);
                        $this->email->attach($lPath, 'attachment', 'CuitBox_LivePhoto_Loop.' . $ext);
                        $attachedCount++;
                        $totalAttachSize += $lSize;
                    }
                }

                // File 4-13: foto jepretan individual (capture_1, capture_2, ...)
                $captureFiles = glob($sessionDir . '/capture_*.jpg');
                if (empty($captureFiles)) {
                    $captureFiles = glob($sessionDir . '/capture_*.png');
                }
                // Sort secara natural agar urutannya 1,2,3,...,10 bukan 1,10,2,...
                natsort($captureFiles);

                foreach ($captureFiles as $capFile) {
                    $cSize = filesize($capFile);
                    if (($totalAttachSize + $cSize) < $MAX_TOTAL_SIZE) {
                        $num = basename($capFile);
                        $this->email->attach($capFile, 'attachment', 'CuitBox_Photo_' . $num);
                        $attachedCount++;
                        $totalAttachSize += $cSize;
                    }
                }
            }
        }

        $hasLink = !empty($folderLink) && strpos($folderLink, 'drive.google.com') !== false;

        $bodyHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #1e293b; line-height: 1.6;'>
            <div style='background: linear-gradient(135deg, #6366f1, #a855f7); padding: 24px; border-radius: 12px 12px 0 0; text-align: center; color: white;'>
                <h1 style='margin: 0; font-size: 24px;'>🎉 CuitBox Studio</h1>
                <p style='margin: 4px 0 0; opacity: 0.9;'>Foto Sesi Anda Siap!</p>
            </div>
            <div style='padding: 24px; background: #ffffff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px;'>
                <p style='font-size: 16px;'>Halo!</p>
                <p>Terima kasih telah berfoto di <strong>CuitBox Studio</strong>. Hasil foto sesi Anda telah kami proses dan terlampir pada email ini.</p>
        ";

        if ($hasLink) {
            $bodyHtml .= "
                <div style='text-align: center; margin: 24px 0;'>
                    <a href='{$folderLink}' target='_blank' style='display: inline-block; background: #6366f1; color: #ffffff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px;'>
                        📁 Buka Google Drive (HD & All Photos)
                    </a>
                </div>
                <p style='color: #64748b; font-size: 13px; text-align: center;'>Atau buka link ini: <br><a href='{$folderLink}' style='color: #6366f1;'>{$folderLink}</a></p>
            ";
        }

        if ($attachedCount > 0) {
            $bodyHtml .= "
                <div style='background: #f8fafc; border-left: 4px solid #6366f1; padding: 12px 16px; margin: 20px 0; border-radius: 4px;'>
                    <p style='margin: 0; color: #334155; font-size: 14px;'>
                        📸 <strong>{$attachedCount} file foto/video photostrip Anda telah dilampirkan langsung (attachment) pada email ini.</strong> Silakan lihat dan unduh lampiran di bawah ini.
                    </p>
                </div>
            ";
        }

        $bodyHtml .= "
                <hr style='border: none; border-top: 1px solid #f1f5f9; margin: 24px 0;'>
                <p style='color: #94a3b8; font-size: 12px; margin: 0; text-align: center;'>
                    CuitBox PhotoBox Studio © 2026. All rights reserved.
                </p>
            </div>
        </div>
        ";

        $this->email->setMessage($bodyHtml);

        if (! $this->email->send()) {
            log_message('error', '[EmailService] Gagal kirim email ke ' . $recipient . ' | Debugger: ' . $this->email->printDebugger(['headers', 'subject', 'body']));
            return false;
        }

        log_message('info', '[EmailService] Email berhasil dikirim ke ' . $recipient . " (Attached: {$attachedCount} files)");
        return true;
    }
}
