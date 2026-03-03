<?php

namespace App\Controllers;

use App\Models\PaymentMethodModel;
use App\Models\GameModel;
use CodeIgniter\Controller;

class PaymentMethodController extends Controller
{
    protected $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentMethodModel();
        helper('url');
    }

    // GET /payment-methods/topup/:game_id
    public function topup($gameId)
    {
        $gameModel = new GameModel();
        
        // Ambil data game
        $game = $gameModel->find($gameId);
        
        if (!$game) {
            return redirect()->to('/games')->with('error', 'Game tidak ditemukan.');
        }

        // Ambil semua metode pembayaran dari database
        $payments = $this->paymentModel->findAll();
        
        // Kirim ke view
        $data = [
            'game' => $game,
            'payments' => $payments
        ];

        return view('payment_methods/topup', $data);
    }

    // POST /payment-methods/process
    public function process()
    {
        $gameId    = $this->request->getPost('game_id');
        $paymentId = $this->request->getPost('payment_method_id');
        $amount    = $this->request->getPost('amount');

        if (!$gameId || !$paymentId || !$amount) {
            return redirect()->back()->with('error', 'Lengkapi semua field terlebih dahulu.');
        }

        // Validasi apakah user sudah login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Simpan ke session sementara
        session()->set('temp_transaction', [
            'game_id'    => $gameId,
            'payment_method_id' => $paymentId,
            'amount'     => $amount,
            'user_id'    => session()->get('user_id')
        ]);

        // Redirect ke form konfirmasi transaksi
        return redirect()->to('/transactions/confirm');
    }
    
    // Method lainnya...
}