<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\FilterModel;
use App\Models\FrameModel;
use App\Models\PrintOptionModel;
use CodeIgniter\HTTP\ResponseInterface;

class CatalogController extends BaseApiController
{
    private BookingModel $bookingModel;
    private PrintOptionModel $printOptionModel;
    private FrameModel $frameModel;
    private FilterModel $filterModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->printOptionModel = new PrintOptionModel();
        $this->frameModel = new FrameModel();
        $this->filterModel = new FilterModel();
    }

    public function listPrintOptions(): ResponseInterface
    {
        $bookingCode = trim((string) $this->request->getGet('booking_code'));
        $booking = $bookingCode === ''
            ? null
            : $this->bookingModel->where('booking_code', $bookingCode)->first();

        $options = $this->printOptionModel->findAll();
        foreach ($options as &$option) {
            $basePrice = 0.00;
            $extraPrice = (float) ($option['extra_price'] ?? 0);
            $option['total_amount'] = number_format($basePrice + $extraPrice, 2, '.', '');
        }

        return $this->respondOk([
            'booking_code' => $bookingCode !== '' ? $bookingCode : null,
            'print_options' => $options,
        ]);
    }

    public function listFrames(): ResponseInterface
    {
        $db = \Config\Database::connect();
        $frames = $db->table('frames f')
            ->select('f.*, l.photo_count as layout_photo_count, l.slot_coordinates as layout_slot_coordinates')
            ->join('frame_layouts l', 'l.id = f.layout_id', 'left')
            ->where('f.is_active', 1)
            ->get()
            ->getResultArray();

        $categoriesList = $db->table('frame_categories')
            ->select('name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $categories = ['All'];
        foreach ($categoriesList as $cat) {
            $categories[] = $cat['name'];
        }

        return $this->respondOk([
            'frames'     => $frames,
            'categories' => $categories,
            'filters'    => $this->filterModel->where('is_active', 1)->findAll(),
        ]);
    }

    public function serveFrameFile(): ResponseInterface
    {
        $path = $this->request->getGet('path');
        if (empty($path)) {
            return $this->respondError('Path is required', 400);
        }
        
        $filePath = FCPATH . str_replace(['../', '..\\'], '', $path);
        if (!file_exists($filePath) || !is_file($filePath)) {
            return $this->respondError('File not found', 404);
        }
        
        $mime = mime_content_type($filePath) ?: 'image/png';
        
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Headers', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
        
        return $this->response
            ->setContentType($mime)
            ->setBody(file_get_contents($filePath));
    }
}
