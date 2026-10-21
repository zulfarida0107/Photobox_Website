<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminCatalogFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $adminRole = strtolower(session()->get('adminRole') ?? '');

        if (!in_array($adminRole, ['admin', 'superadmin'], true)) {
            return redirect()->to('/admin/dashboard')
                ->with('error', 'Akses Ditolak: Fitur ini hanya dapat diakses oleh Admin atau Super Admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
