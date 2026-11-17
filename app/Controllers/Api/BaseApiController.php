<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseApiController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Set global CORS headers on all API responses
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization, X-API-KEY');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE, PATCH');
    }

    protected function getRequestPayload(): array
    {
        try {
            $json = $this->request->getJSON(true);
            if (is_array($json)) {
                return $json;
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        $post = $this->request->getPost();
        if (!empty($post) && is_array($post)) {
            return $post;
        }

        $raw = $this->request->getBody();
        if (!empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    protected function makeId(string $prefix): string
    {
        return $prefix . substr(strtoupper(bin2hex(random_bytes(8))), 0, 16);
    }

    protected function failValidation(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(422)->setJSON([
            'status' => 422,
            'error'  => $message,
        ]);
    }

    protected function failNotFound(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(404)->setJSON([
            'status' => 404,
            'error'  => $message,
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

    protected function failUnauthorized(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(401)->setJSON([
            'status' => 401,
            'error'  => $message,
        ]);
    }

    protected function failForbidden(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(403)->setJSON([
            'status' => 403,
            'error'  => $message,
        ]);
    }

    protected function failServerError(string $message): ResponseInterface
    {
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 500,
            'error'  => $message,
        ]);
    }
}
