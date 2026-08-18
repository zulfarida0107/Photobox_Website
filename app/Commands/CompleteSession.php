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

        try {
            CLI::write("Uploading photos to Google Drive...");
            $driveService = new GoogleDriveService();
            $driveLink = $driveService->uploadSessionPhotos($sessionId);
            CLI::write("Drive folder created: {$driveLink}");

            CLI::write("Sending email with link to recipient: {$session['email_recipient']}...");
            $emailService = new EmailService();
            $sent = $emailService->sendDriveLink(
                $session['email_recipient'],
                $driveLink
            );

            if (!$sent) {
                throw new \RuntimeException('Failed to send email.');
            }

            CLI::write("Email sent successfully!");

            $bookingModel->update($booking['id'], [
                'status' => 'completed',
            ]);

            CLI::write("Session completion process finished successfully!");
        } catch (\Throwable $e) {
            log_message('error', "Background CompleteSession Error for session #{$sessionId}: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            CLI::error("Error: " . $e->getMessage());
        }
    }
}
