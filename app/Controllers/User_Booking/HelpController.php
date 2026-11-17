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

        $isAjax = $this->request->isAJAX() 
               || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest'
               || strpos($this->request->getHeaderLine('Accept'), 'application/json') !== false;

        if (!$this->validate($rules)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => implode(', ', $this->validator->getErrors())
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Kirim email ke admin
        $emailService = \Config\Services::email();
        $emailService->setFrom('cuitboxstudio@gmail.com', 'CuitBox Customer Help');
        $emailService->setTo('cuitboxstudio@gmail.com');
        $emailService->setReplyTo($email, $name);
        $emailService->setSubject('[PENTING] [Pusat Bantuan] ' . $subject . ' - Dari: ' . $name);
        
        // Tandai sebagai Email Penting (High Priority Headers)
        $emailService->setPriority(1); // 1 = Highest Priority
        $emailService->setHeader('X-Priority', '1');
        $emailService->setHeader('Importance', 'High');
        $emailService->setHeader('X-MSMail-Priority', 'High');
        $emailService->setHeader('X-Message-Flag', 'Pusat Bantuan - Penting');
        
        $htmlMessage = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #ffffff;">
            <div style="background-color: #ef4444; color: #ffffff; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 11px; font-weight: bold; margin-bottom: 12px;">
                🚨 PUSAT BANTUAN - PENTING
            </div>
            <h3 style="margin-top: 0; color: #1e293b;">Pertanyaan / Kendala dari Customer</h3>
            <p><strong>Nama:</strong> ' . esc($name) . '</p>
            <p><strong>Email Pengirim:</strong> ' . esc($email) . '</p>
            <p><strong>Subjek:</strong> ' . esc($subject) . '</p>
            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 16px 0;">
            <p style="font-weight: bold; color: #475569; margin-bottom: 4px;">Isi Pesan:</p>
            <div style="background-color: #f8fafc; padding: 12px 16px; border-radius: 8px; color: #334155; line-height: 1.5;">
                ' . nl2br(esc($message)) . '
            </div>
        </div>
        ';
        
        $emailService->setMessage($htmlMessage);
        
        if ($emailService->send()) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Pesan Anda berhasil dikirim! Tim kami akan segera merespon.'
                ]);
            }
            return redirect()->to('/help')->with('success', 'Pesan Anda berhasil dikirim! Tim kami akan segera merespon.');
        } else {
            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal mengirim pesan. Silakan coba lagi.'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }
    }
}