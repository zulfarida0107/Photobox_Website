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
        $adminModel = new \App\Models\AdminModel();

        // Refresh the role from the database so role changes take effect immediately.
        if ($adminId) {
            $admin = $adminModel->find($adminId);
            if ($admin && in_array(strtolower($admin['role']), ['superadmin', 'admin', 'staff'], true)) {
                session()->set([
                    'adminName'  => $admin['name'],
                    'adminEmail' => $admin['email'],
                    'adminRole'  => strtolower($admin['role']),
                    'isLoggedIn' => true,
                ]);
                return;
            }

            session()->remove(['adminId', 'adminName', 'adminEmail', 'adminRole', 'isLoggedIn']);
        }

        // 2. Cek auto-login via remember me cookie bertanda tangan HMAC
        helper('cookie');
        $cookieToken = get_cookie('remember_admin');

        if ($cookieToken) {
            $decoded = base64_decode($cookieToken, true);
            if ($decoded && strpos($decoded, ':') !== false) {
                [$cAdminId, $cSignature] = explode(':', $decoded, 2);
                $admin = $adminModel->find($cAdminId);
                
                if ($admin && in_array(strtolower($admin['role']), ['superadmin', 'admin', 'staff'], true)) {
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
