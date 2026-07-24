<?php

namespace App\Services;

use Config\Services;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    public function sendDriveLink(
        string $recipient,
        string $folderLink
    ): bool {

        $this->email->clear();

        $this->email->setTo($recipient);

        $this->email->setSubject(
            'PhotoBox - Foto Anda'
        );

        $this->email->setMailType('html');

        $this->email->setMessage("
            <h2>Terima kasih telah menggunakan PhotoBox.</h2>

            <p>
                Seluruh foto Anda telah selesai diproses.
            </p>

            <p>
                Silakan download melalui link berikut:
            </p>

            <p>
                <a href='{$folderLink}'>
                    {$folderLink}
                </a>
            </p>

            <br>

            <p>
                Salam,
                <br>
                PhotoBox Studio
            </p>
        ");

        if (! $this->email->send()) {

            log_message(
                'error',
                $this->email->printDebugger(['headers'])
            );

            return false;
        }

        return true;
    }
}
