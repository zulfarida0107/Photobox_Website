<?php

namespace App\Controllers\Api;

use App\Models\BookingModel;
use App\Models\FilterModel;
use App\Models\FrameModel;
use App\Models\PackageModel;
use App\Models\PrintOptionModel;
use CodeIgniter\HTTP\ResponseInterface;

class CatalogController extends BaseApiController
{
    private BookingModel $bookingModel;
    private PackageModel $packageModel;
    private PrintOptionModel $printOptionModel;
    private FrameModel $frameModel;
    private FilterModel $filterModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->packageModel = new PackageModel();
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

        $package = null;
        if ($booking !== null) {
            $package = $this->packageModel->find($booking['package_id']);
        }

        $options = $this->printOptionModel->findAll();
        foreach ($options as &$option) {
            $basePrice = (float) ($package['base_price'] ?? 0);
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
        $frames = $this->frameModel
            ->select('frames.*, frame_categories.name as category')
            ->join('frame_categories', 'frame_categories.id = frames.category_id', 'left')
            ->where('frames.is_active', 1)
            ->findAll();

        $db = \Config\Database::connect();
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
}
