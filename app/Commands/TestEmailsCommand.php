<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestEmailsCommand extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:emails';
    protected $description = 'Remote test sending 3 email types: Pusat Bantuan (Penting), Tiket Booking (Penting dengan Grid Hijau QR), and Hasil Foto (Terkirim)';

    public function run(array $params)
    {
        $targetEmail = $params[0] ?? 'cuitboxstudio@gmail.com';
        
        CLI::write("===============================================", "yellow");
        CLI::write("  REMOTE TESTING PENGIRIMAN 3 TIPE EMAIL", "green");
        CLI::write("===============================================\n", "yellow");

        // 1. TEST PUSAT BANTUAN
        CLI::write("[1/3] Mengirim Test Email: 📩 PUSAT BANTUAN (Masuk PENTING)...", "cyan");
        $emailService = \Config\Services::email();
        $emailService->clear(true);
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Customer Help');
        $emailService->setTo($targetEmail);
        $emailService->setReplyTo('tester@customer.com', 'Rarida Tester');
        $emailService->setSubject('[PENTING] [Pusat Bantuan] Uji Coba Kendala Reschedule - Dari: Rarida Tester');

        $emailService->setPriority(1);
        $emailService->setHeader('X-Priority', '1');
        $emailService->setHeader('Importance', 'High');
        $emailService->setHeader('X-MSMail-Priority', 'High');
        $emailService->setHeader('X-Message-Flag', 'Pusat Bantuan - Penting');

        $htmlHelp = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #ffffff;">
            <div style="background-color: #ef4444; color: #ffffff; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 11px; font-weight: bold; margin-bottom: 12px;">
                🚨 PUSAT BANTUAN - PENTING
            </div>
            <h3 style="margin-top: 0; color: #1e293b;">Pertanyaan / Kendala dari Customer</h3>
            <p><strong>Nama:</strong> Rarida Tester</p>
            <p><strong>Email Pengirim:</strong> tester@customer.com</p>
            <p><strong>Subjek:</strong> Uji Coba Kendala Reschedule</p>
            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 16px 0;">
            <p style="font-weight: bold; color: #475569; margin-bottom: 4px;">Isi Pesan:</p>
            <div style="background-color: #f8fafc; padding: 12px 16px; border-radius: 8px; color: #334155; line-height: 1.5;">
                Ini adalah pesan pengujian sistem Pusat Bantuan CuitBox Studio untuk memastikan email masuk ke kategori Penting.
            </div>
        </div>
        ';
        $emailService->setMessage($htmlHelp);

        if ($emailService->send()) {
            CLI::write("  -> SUKSES! Email Pusat Bantuan berhasil dikirim.", "green");
        } else {
            CLI::error("  -> GAGAL: " . print_r($emailService->printDebugger(['headers']), true));
        }

        // 2. TEST TIKET BOOKING DENGAN GRID HIJAU QR CODE RESMI
        CLI::write("\n[2/3] Mengirim Test Email: 🎟️ TIKET BOOKING (Dengan Grid Hijau & QR Code)...", "cyan");
        $emailService->clear(true);
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Studio');
        $emailService->setTo($targetEmail);
        
        $bookingCode = (string) rand(100000, 999999);
        $emailService->setSubject('[TIKET BOOKING] Tiket Resmi Pemesanan #' . $bookingCode . ' - CuitBox Studio');

        $emailService->setPriority(1);
        $emailService->setHeader('X-Priority', '1');
        $emailService->setHeader('Importance', 'High');
        $emailService->setHeader('X-MSMail-Priority', 'High');
        $emailService->setHeader('X-Message-Flag', 'Tiket Booking Resmi');

        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($bookingCode);

        $htmlTicket = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; }
                .ticket-container { max-width: 520px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
                .brand-title { font-size: 22px; font-weight: 800; color: #0f172a; text-align: center; margin-top: 24px; }
                .brand-subtitle { font-size: 13px; font-weight: 600; color: #64748b; text-align: center; margin-bottom: 20px; }
                .user-info-box { padding: 0 24px 20px; }
                .card-green { background-color: #D9F99D; padding: 24px; text-align: center; border-radius: 16px; margin: 0 20px 20px; }
                .code-label { font-size: 11px; font-weight: 800; color: #365314; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 4px; }
                .code-value { font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 16px; letter-spacing: 1px; }
                .qr-box { background: #ffffff; padding: 12px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
                .note-pill { display: inline-block; background: rgba(54, 83, 20, 0.15); color: #365314; font-size: 11px; font-weight: 700; padding: 6px 14px; border-radius: 20px; margin-top: 14px; }
                .footer-text { text-align: center; font-size: 11px; color: #94a3b8; padding-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="ticket-container">
                <div class="brand-title">CuitBox Studio</div>
                <div class="brand-subtitle">Tiket Resmi & Konfirmasi Pemesanan</div>

                <!-- Detail Pemesan & Pemesanan -->
                <div class="user-info-box">
                    <table style="width:100%; border-collapse:collapse; font-size:13px;">
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Atas Nama Pemesanan:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">Rarida Tester</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Email Terdaftar:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">' . esc($targetEmail) . '</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Booth:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">Booth Studio A</td>
                        </tr>
                        <tr style="border-bottom: 1px dashed #cbd5e1;">
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Waktu Sesi:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">26 Agustus 2026 • 14:00 - 15:00 WIB</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color:#64748b; font-weight:600;">Nomor Antrean:</td>
                            <td style="padding: 8px 0; color:#0f172a; font-weight:700; text-align:right;">#01</td>
                        </tr>
                    </table>
                </div>

                <!-- Kartu Hijau Kode Booking & QR Code -->
                <div class="card-green">
                    <div class="code-label">KODE BOOKING ANDA</div>
                    <div class="code-value">' . esc($bookingCode) . '</div>
                    <div class="qr-box">
                        <img src="' . $qrCodeUrl . '" alt="QR Code Booking" width="180" height="180" style="display:block;">
                    </div>
                    <br>
                    <div class="note-pill">Scan atau masukkan kode ini di photobox</div>
                </div>

                <div class="footer-text">© 2026 CuitBox Studio. All rights reserved.</div>
            </div>
        </body>
        </html>
        ';
        $emailService->setMessage($htmlTicket);

        if ($emailService->send()) {
            CLI::write("  -> SUKSES! Email Tiket Booking dengan Grid Hijau & QR Code berhasil dikirim.", "green");
        } else {
            CLI::error("  -> GAGAL: " . print_r($emailService->printDebugger(['headers']), true));
        }

        // 3. TEST HASIL FOTO DARI DESKTOP
        CLI::write("\n[3/3] Mengirim Test Email: 📷 HASIL FOTO JEPRETAN...", "cyan");
        $emailService->clear(true);
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Photo Booth');
        $emailService->setTo($targetEmail);
        $emailService->setSubject('📸 Foto Photobox Anda - CuitBox Studio #' . $bookingCode);

        $htmlPhoto = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h1 style="color: #0f172a; margin: 0 0 8px 0; font-size: 24px;">🎉 Terima Kasih Telah Berfoto di CuitBox!</h1>
                <p style="color: #64748b; margin: 0; font-size: 14px;">Sesi foto Anda telah selesai diproses.</p>
            </div>
            
            <p style="color: #334155; font-size: 15px; line-height: 1.6;">
                Halo! Seluruh file foto (10 jepretan + 1 frame foto final) <strong>sudah kami lampirkan langsung di email ini</strong>. Anda bisa langsung mengunduh dan menyimpannya ke galeri ponsel Anda.
            </p>

            <div style="text-align: center; margin: 28px 0;">
                <a href="https://drive.google.com" style="background-color: #e2f952; color: #0f172a; padding: 14px 32px; text-decoration: none; border-radius: 50px; font-weight: 800; font-size: 15px; display: inline-block; box-shadow: 0 4px 15px rgba(226,249,82,0.4);">
                    ☁️ Buka Folder Google Drive
                </a>
            </div>

            <div style="background-color: #f8fafc; border-radius: 12px; padding: 16px; margin: 20px 0; text-align: center; border: 1px dashed #cbd5e1;">
                <p style="color: #475569; font-size: 13.5px; margin: 0; font-weight: bold;">
                    📎 Lihat lampiran di bawah email ini untuk mengunduh semua foto dalam resolusi penuh!
                </p>
            </div>

            <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 24px 0;">

            <p style="color: #94a3b8; font-size: 12px; text-align: center; margin: 0;">
                © 2026 <strong>CuitBox Studio</strong>. All rights reserved.
            </p>
        </div>
        ';
        $emailService->setMessage($htmlPhoto);

        if ($emailService->send()) {
            CLI::write("  -> SUKSES! Email Hasil Foto berhasil dikirim.", "green");
        } else {
            CLI::error("  -> GAGAL: " . print_r($emailService->printDebugger(['headers']), true));
        }

        CLI::write("\n===============================================", "yellow");
        CLI::write("  PENGUJIAN SELESAI! Silakan cek Gmail Anda.", "green");
        CLI::write("===============================================\n", "yellow");
    }
}
