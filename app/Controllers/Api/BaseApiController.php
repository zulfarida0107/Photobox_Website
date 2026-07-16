<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

abstract class BaseApiController extends BaseController
{
    protected function makeId(string $prefix): string
    {
        return $prefix . substr(strtoupper(bin2hex(random_bytes(8))), 0, 16);
    }

    protected function failValidation(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(422)->setJSON([
            'status' => 422,
            'error' => $message,
        ]);
    }

    protected function failNotFound(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(404)->setJSON([
            'status' => 404,
            'error' => $message,
        ]);
    }

    protected function respondOk(array $data): ResponseInterface
    {
        return $this->response->setStatusCode(200)->setJSON($data);
    }

    protected function respondCreated(array $data): ResponseInterface
    {
        return $this->response->setStatusCode(201)->setJSON($data);
    }

    protected function failServerError(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 500,
            'error'  => $message,
        ]);
    }
}
