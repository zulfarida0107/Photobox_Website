<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SuperadminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $adminRole = strtolower(session()->get('adminRole') ?? '');

        // Hanya role 'superadmin' yang diizinkan
        if ($adminRole !== 'superadmin') {
            return redirect()->to('/admin/dashboard')
                ->with('error', 'Akses Ditolak: Fitur ini hanya dapat diakses oleh Super Admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
