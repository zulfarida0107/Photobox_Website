<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Sender
     */
    public string $fromEmail = '';
    public string $fromName = '';
    public string $recipients = '';

    /**
     * User Agent
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * Protocol
     */
    public string $protocol = 'smtp';

    /**
     * Sendmail Path
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Configuration
     */
    public string $SMTPHost = '';
    public string $SMTPAuthMethod = 'login';
    public string $SMTPUser = '';
    public string $SMTPPass = '';
    public int $SMTPPort = 587;
    public int $SMTPTimeout = 30;
    public bool $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls';

    /**
     * Mail Settings
     */
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public string $mailType = 'html';
    public string $charset = 'UTF-8';

    /**
     * Validation
     */
    public bool $validate = true;
    public int $priority = 3;

    /**
     * New Line
     */
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";

    /**
     * BCC
     */
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;

    /**
     * Delivery Status Notification
     */
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        $this->fromEmail = env('email.fromEmail', '');
        $this->fromName = env('email.fromName', 'PhotoBox');

        $this->protocol = env('email.protocol', 'smtp');

        $this->SMTPHost = env('email.SMTPHost', '');
        $this->SMTPUser = env('email.SMTPUser', '');
        $this->SMTPPass = env('email.SMTPPass', '');
        $this->SMTPPort = (int) env('email.SMTPPort', 587);
        $this->SMTPCrypto = env('email.SMTPCrypto', 'tls');

        $this->mailType = env('email.mailType', 'html');
        $this->charset = env('email.charset', 'UTF-8');
    }
}