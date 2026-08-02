<?php

namespace App\Controllers\User_Booking;

use App\Controllers\BaseController;
use App\Models\PrintOptionModel;

class PrintOptionController extends BaseController
{
    protected $printOptionModel;

    public function __construct()
    {
        $this->printOptionModel = new PrintOptionModel();
    }

    public function index()
    {
        // Ambil opsi cetak (print options) yang diambil dari Admin
        $printOptions = $this->printOptionModel->orderBy('copies', 'ASC')->findAll();

        $data = [
            'title' => 'Pilih Harga Cetak - CuitBox Studio',
            'print_options' => $printOptions,
        ];

        return view('User_Booking/print_option', $data);
    }

    public function store()
    {
        $printOptionId = $this->request->getPost('print_option_id');

        if (empty($printOptionId)) {
            return redirect()->back()->with('error', 'Silakan pilih jumlah cetak terlebih dahulu.');
        }

        // Cek apakah print option ada di database
        $printOption = $this->printOptionModel->find($printOptionId);
        if (!$printOption) {
            return redirect()->back()->with('error', 'Pilihan cetak tidak ditemukan.');
        }

        // Simpan pilihan ke session
        session()->set('selected_print_option_id', $printOptionId);
        session()->set('selected_print_option_name', $printOption['copies'] . ' Cetak');
        session()->set('selected_print_option_price', $printOption['extra_price']);

        // Redirect ke halaman pilih tema frame
        return redirect()->to('/frame')->with('success', 'Paket "' . $printOption['copies'] . ' Cetak" berhasil dipilih!');
    }
}
