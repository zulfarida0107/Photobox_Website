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

        // 2. Cek auto-login via remember me cookie
        helper('cookie');
        $cookieAdminId = get_cookie('remember_admin');
        
        if ($cookieAdminId) {
            $adminModel = new \App\Models\AdminModel();
            $admin = $adminModel->find($cookieAdminId);
            
            if ($admin && in_array(strtolower($admin['role']), ['superadmin', 'staff'])) {
                session()->set([
                    'adminId'    => $admin['id'],
                    'adminName'  => $admin['name'],
                    'adminEmail' => $admin['email'],
                    'adminRole'  => strtolower($admin['role']),
                    'isLoggedIn' => true,
                ]);
                return; // Berhasil auto-login via cookie
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
