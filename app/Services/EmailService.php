<?php

namespace App\Services;

use Config\Services;

class EmailService
{
    public function sendDriveLink(
        string $recipient,
        string $folderLink,
        $attachments = []
    ): bool {
        ini_set('memory_limit', '512M');
        $config = config(\Config\Email::class);
        $config->protocol = 'smtp';
        $config->SMTPHost = 'smtp.gmail.com';
        $config->SMTPUser = 'cuitboxstudio@gmail.com';
        $config->SMTPPass = 'mmnyzpjjpcvdxrio';
        $config->SMTPPort = 465;
        $config->SMTPCrypto = 'ssl';
        $config->SMTPAuthMethod = 'login';
        $config->fromEmail = 'cuitboxstudio@gmail.com';
        $config->fromName = 'CuitBox Photo Booth';
        $config->mailType = 'html';
        $config->charset = 'UTF-8';
        $config->newline = "\r\n";
        $config->CRLF = "\r\n";
        $config->SMTPTimeout = 120;

        $email = new \CodeIgniter\Email\Email($config);
        $email->clear(true);
        $email->setFrom($config->fromEmail, $config->fromName);
        $email->setTo($recipient);
        $email->setSubject('📸 Foto Photobox Anda - CuitBox Studio');
        $email->setMailType('html');
        $email->setCRLF("\r\n");
        $email->setNewline("\r\n");

        $isCloudLink = str_contains($folderLink, 'drive.google.com');
        $linkSection = '';
        if ($isCloudLink) {
            $linkSection = "
            <div style='text-align: center; margin: 28px 0;'>
                <a href='{$folderLink}' style='background-color: #e2f952; color: #0f172a; padding: 14px 32px; text-decoration: none; border-radius: 50px; font-weight: 800; font-size: 15px; display: inline-block; box-shadow: 0 4px 15px rgba(226,249,82,0.4);'>
                    ☁️ Buka Folder Google Drive
                </a>
            </div>
            <p style='color: #64748b; font-size: 12px; text-align: center; margin-bottom: 20px;'>
                Atau salin tautan berikut: <a href='{$folderLink}' style='color: #3b82f6;'>{$folderLink}</a>
            </p>
            ";
        }

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h1 style='color: #0f172a; margin: 0 0 8px 0; font-size: 24px;'>🎉 Terima Kasih Telah Berfoto di CuitBox!</h1>
                <p style='color: #64748b; margin: 0; font-size: 14px;'>Sesi foto Anda telah selesai diproses.</p>
            </div>
            
            <p style='color: #334155; font-size: 15px; line-height: 1.6;'>
                Halo! Seluruh file foto (10 jepretan + 1 frame foto final) <strong>sudah kami lampirkan langsung di email ini</strong>. Anda bisa langsung mengunduh dan menyimpannya ke galeri ponsel Anda.
            </p>

            {$linkSection}

            <div style='background-color: #f8fafc; border-radius: 12px; padding: 16px; margin: 20px 0; text-align: center; border: 1px dashed #cbd5e1;'>
                <p style='color: #475569; font-size: 13.5px; margin: 0; font-weight: bold;'>
                    📎 Lihat lampiran di bawah email ini untuk mengunduh semua foto dalam resolusi penuh!
                </p>
            </div>

            <hr style='border: none; border-top: 1px solid #f1f5f9; margin: 24px 0;'>

            <p style='color: #94a3b8; font-size: 12px; text-align: center; margin: 0;'>
                © 2026 <strong>CuitBox Studio</strong>. All rights reserved.
            </p>
        </div>
        ";

        $email->setMessage($body);

        // Lampirkan file (bisa single string atau array of files)
        $filesToAttach = is_array($attachments) ? $attachments : (array) $attachments;
        foreach ($filesToAttach as $filePath) {
            if ($filePath && file_exists($filePath)) {
                $email->attach($filePath);
            }
        }

        if (! $email->send()) {
            $debug = $email->printDebugger(['headers', 'subject']);
            log_message('error', 'Email sending failed to ' . $recipient . ': ' . $debug);
            return false;
        }

        log_message('info', 'Email successfully sent to ' . $recipient . ' with ' . count($filesToAttach) . ' attachments.');
        return true;
    }
}
