<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\SessionModel;
use App\Models\BookingModel;
use App\Services\GoogleDriveService;
use App\Services\EmailService;

class CompleteSession extends BaseCommand
{
    protected $group       = 'Session';
    protected $name        = 'session:complete';
    protected $description = 'Uploads session photos to Google Drive and sends email link in background';
    protected $usage       = 'session:complete [session_id]';
    protected $arguments   = [
        'session_id' => 'The ID of the session to complete'
    ];

    public function run(array $params)
    {
        $sessionId = $params[0] ?? null;

        if (empty($sessionId)) {
            CLI::error('Session ID is required.');
            return;
        }

        CLI::write("Processing session #{$sessionId} in background...");

        $sessionModel = new SessionModel();
        $bookingModel = new BookingModel();

        $session = $sessionModel->find($sessionId);
        if (!$session) {
            log_message('error', "Background CompleteSession: Session #{$sessionId} not found.");
            CLI::error("Session not found.");
            return;
        }

        $booking = $bookingModel->find($session['booking_id']);
        if (!$booking) {
            log_message('error', "Background CompleteSession: Booking #{$session['booking_id']} not found.");
            CLI::error("Booking not found.");
            return;
        }

        $driveLink = '';
        try {
            CLI::write("Uploading photos to Google Drive...");
            $driveService = new GoogleDriveService();
            $driveLink = $driveService->uploadSessionPhotos($sessionId);
            CLI::write("Google Drive upload success: {$driveLink}", 'green');
        } catch (\Throwable $driveError) {
            log_message('warning', "Google Drive upload skipped/failed for session #{$sessionId}: " . $driveError->getMessage());
            CLI::write("Google Drive notice: " . $driveError->getMessage(), 'yellow');
            // Fallback link ke URL web lokal
            $driveLink = base_url("uploads/sessions/{$sessionId}/final.png");
        }

        // Cari file frame foto final (final.png) untuk dilampirkan langsung ke email
        $attachments = [];
        $sessionDirWritable = WRITEPATH . "uploads/sessions/{$sessionId}/";
        $sessionDirPublic = FCPATH . "uploads/sessions/{$sessionId}/";

        if (file_exists($sessionDirWritable . 'final.png')) {
            $attachments[] = $sessionDirWritable . 'final.png';
        } elseif (file_exists($sessionDirPublic . 'final.png')) {
            $attachments[] = $sessionDirPublic . 'final.png';
        }

        try {
            CLI::write("Sending email with " . count($attachments) . " photo attachments to: {$session['email_recipient']}...");
            $emailService = new EmailService();
            $sent = $emailService->sendDriveLink(
                $session['email_recipient'],
                $driveLink,
                $attachments
            );

            if (!$sent) {
                throw new \RuntimeException('Gagal mengirim email lewat SMTP.');
            }

            CLI::write("Email sent successfully to {$session['email_recipient']}!", 'green');

            $bookingModel->update($booking['id'], [
                'status' => 'completed',
            ]);

            CLI::write("Session completion process finished successfully!", 'green');
        } catch (\Throwable $e) {
            log_message('error', "Background CompleteSession Error for session #{$sessionId}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            CLI::error("Error: " . $e->getMessage());
        }
    }
}
