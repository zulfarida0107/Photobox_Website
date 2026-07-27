<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use CodeIgniter\Email\Email;

class HelpController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Pusat Bantuan - CuitBox Studio'
        ];
        return view('User_Booking/help', $data);
    }

    public function send()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');

        // Validasi
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[5]',
            'message' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kirim email ke admin
        $emailService = \Config\Services::email();
        
        $config = [
            'protocol'  => 'smtp',
            'SMTPHost'  => 'smtp.gmail.com',
            'SMTPUser'  => 'your-email@gmail.com',
            'SMTPPass'  => 'your-app-password',
            'SMTPPort'  => 587,
            'SMTPCrypto' => 'tls',
            'mailType'  => 'html',
            'charset'   => 'utf-8'
        ];

        $emailService->initialize($config);
        $emailService->setFrom($email, $name);
        $emailService->setTo('support@cuitbox.com');
        $emailService->setSubject('Pertanyaan dari ' . $name . ' - ' . $subject);
        
        $htmlMessage = '
        <h3>Pertanyaan dari Customer</h3>
        <p><strong>Nama:</strong> ' . $name . '</p>
        <p><strong>Email:</strong> ' . $email . '</p>
        <p><strong>Subjek:</strong> ' . $subject . '</p>
        <hr>
        <p><strong>Pesan:</strong></p>
        <p>' . nl2br($message) . '</p>
        ';
        
        $emailService->setMessage($htmlMessage);
        
        if ($emailService->send()) {
            return redirect()->to('/help')->with('success', 'Pesan Anda berhasil dikirim! Tim kami akan segera merespon.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }
    }
}