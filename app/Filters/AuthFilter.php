<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            helper('cookie');
            $adminId = get_cookie('remember_admin');
            
            if ($adminId) {
                $adminModel = new \App\Models\AdminModel();
                $admin = $adminModel->find($adminId);
                
                if ($admin) {
                    session()->set([
                        'adminId'    => $admin['id'],
                        'adminName'  => $admin['name'],
                        'adminRole'  => $admin['role'],
                        'isLoggedIn' => true,
                    ]);
                    return; // Berhasil auto-login via cookie
                }
            }

            return redirect()->to('/admin/login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
