<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $adminId   = session()->get('adminId');
        $adminRole = strtolower(session()->get('adminRole') ?? '');

        // 1. Cek apakah session admin yang sah (superadmin / staff) sudah aktif
        if ($adminId && in_array($adminRole, ['superadmin', 'staff'])) {
            return; // Lolos verifikasi
        }

        // 2. Cek auto-login via remember me cookie bertanda tangan HMAC
        helper('cookie');
        $cookieToken = get_cookie('remember_admin');
        
        if ($cookieToken) {
            $decoded = base64_decode($cookieToken, true);
            if ($decoded && strpos($decoded, ':') !== false) {
                [$cAdminId, $cSignature] = explode(':', $decoded, 2);
                $adminModel = new \App\Models\AdminModel();
                $admin = $adminModel->find($cAdminId);
                
                if ($admin && in_array(strtolower($admin['role']), ['superadmin', 'staff'])) {
                    $expectedSig = hash_hmac('sha256', $admin['id'] . $admin['password'], config('App')->baseURL);
                    if (hash_equals($expectedSig, $cSignature)) {
                        session()->set([
                            'adminId'    => $admin['id'],
                            'adminName'  => $admin['name'],
                            'adminEmail' => $admin['email'],
                            'adminRole'  => strtolower($admin['role']),
                            'isLoggedIn' => true,
                        ]);
                        return; // Berhasil auto-login via verified cookie
                    }
                }
            }
        }

        // 3. Jika bukan admin yang sah, arahkan ke login admin
        return redirect()->to('/admin/login')
            ->with('error', 'Silakan login sebagai Administrator terlebih dahulu.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
