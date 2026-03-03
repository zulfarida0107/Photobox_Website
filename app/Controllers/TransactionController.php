<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\GameModel;
use App\Models\PaymentMethodModel;

class TransactionController extends BaseController
{
    protected $transactionModel;
    protected $gameModel;
    protected $paymentMethodModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->gameModel = new GameModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        helper('url');
    }

    // ============= METHOD INDEX =============
    public function index()
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login');
        }

        $userId = session()->get('user_id');
        
        // Ambil semua transaksi user
        $transactions = $this->transactionModel
            ->select('transactions.*, games.name as game_name, games.category, payment_methods.name as payment_name, payment_methods.type as payment_type')
            ->join('games', 'games.id = transactions.game_id', 'left')
            ->join('payment_methods', 'payment_methods.id = transactions.payment_method_id', 'left')
            ->where('transactions.user_id', $userId)
            ->orderBy('transactions.created_at', 'DESC')
            ->findAll();

        return view('transactions/index', ['transactions' => $transactions]);
    }

    // ============= METHOD CONFIRM =============
    public function confirm()
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil data sementara dari session
        $tempTransaction = session()->get('temp_transaction');
        
        if (!$tempTransaction) {
            return redirect()->to('/games')->with('error', 'Tidak ada data transaksi');
        }

        // Ambil data game
        $game = $this->gameModel->find($tempTransaction['game_id']);
        
        // Ambil data payment method
        $paymentMethod = $this->paymentMethodModel->find($tempTransaction['payment_method_id']);

        if (!$game || !$paymentMethod) {
            session()->remove('temp_transaction');
            return redirect()->to('/games')->with('error', 'Data tidak valid');
        }

        // Tampilkan halaman konfirmasi
        return view('transactions/confirm', [
            'game' => $game,
            'paymentMethod' => $paymentMethod,
            'amount' => $tempTransaction['amount']
        ]);
    }

    // ============= METHOD STORE =============
    public function store()
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil data dari POST atau session
        $gameId = $this->request->getPost('game_id') ?? session()->get('temp_transaction.game_id');
        $paymentId = $this->request->getPost('payment_method_id') ?? session()->get('temp_transaction.payment_method_id');
        $amount = $this->request->getPost('amount') ?? session()->get('temp_transaction.amount');

        // Validasi
        if (!$gameId || !$paymentId || !$amount) {
            return redirect()->back()->with('error', 'Data transaksi tidak lengkap');
        }

        // Validasi payment method
        $paymentMethod = $this->paymentMethodModel->find($paymentId);
        if (!$paymentMethod) {
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid');
        }

        // Generate invoice
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

        // Data transaksi
        $transactionData = [
            'invoice_number' => $invoiceNumber,
            'user_id' => session()->get('user_id'),
            'game_id' => $gameId,
            'payment_method_id' => $paymentId,
            'amount' => $amount,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Simpan ke database
        if ($this->transactionModel->insert($transactionData)) {
            // Hapus session
            session()->remove('temp_transaction');
            
            // Redirect ke halaman instruksi pembayaran
            return redirect()->to('/transactions/payment-instructions/' . $this->transactionModel->getInsertID())
                           ->with('success', 'Transaksi berhasil dibuat, silakan lakukan pembayaran');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi');
        }
    }

    // ============= METHOD PAYMENT INSTRUCTIONS =============
    public function paymentInstructions($id)
    {
        // Log debugging
        log_message('debug', '===== PAYMENT INSTRUCTIONS DIPANGGIL =====');
        log_message('debug', 'ID: ' . $id);
        
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login');
        }

        $userId = session()->get('user_id');
        
        // Ambil data transaksi
        $transaction = $this->transactionModel
            ->select('transactions.*, games.name as game_name, games.category, payment_methods.name as payment_name, payment_methods.type as payment_type')
            ->join('games', 'games.id = transactions.game_id', 'left')
            ->join('payment_methods', 'payment_methods.id = transactions.payment_method_id', 'left')
            ->where('transactions.id', $id)
            ->where('transactions.user_id', $userId)
            ->first();

        // Cek transaksi
        if (!$transaction) {
            log_message('error', 'Transaksi tidak ditemukan - ID: ' . $id . ', User: ' . $userId);
            return redirect()->to('/transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        // Cek status
        if ($transaction['status'] != 'pending') {
            log_message('debug', 'Status transaksi bukan pending: ' . $transaction['status']);
            return redirect()->to('/transactions/detail/' . $id)->with('error', 'Transaksi sudah diproses');
        }

        log_message('debug', 'Menampilkan view payment_instructions untuk ID: ' . $id);
        
        // Tampilkan view
        return view('transactions/payment_instructions', ['transaction' => $transaction]);
    }

    // ============= METHOD UPLOAD PROOF =============
    public function uploadProof($id)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login');
        }

        $userId = session()->get('user_id');
        
        // Ambil data transaksi
        $transaction = $this->transactionModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$transaction) {
            return redirect()->to('/transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        // Proses file upload
        $file = $this->request->getFile('payment_proof');
        
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Pilih file bukti pembayaran terlebih dahulu');
        }

        // Cek ukuran file
        if ($file->getSize() > 2048 * 1024) {
            return redirect()->back()->with('error', 'Ukuran file maksimal 2MB');
        }

        // Cek ekstensi
        $validExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $extension = $file->getExtension();
        
        if (!in_array(strtolower($extension), $validExtensions)) {
            return redirect()->back()->with('error', 'Format file harus JPG, JPEG, PNG, atau PDF');
        }

        // Buat folder upload
        $uploadPath = FCPATH . 'uploads/bukti_pembayaran';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate nama file
        $newName = $file->getRandomName();
        
        // Pindahkan file
        if ($file->move($uploadPath, $newName)) {
            // Update status langsung success
            $this->transactionModel->update($id, [
                'status' => 'success',
                'payment_proof' => $newName,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            return redirect()->to('/transactions/detail/' . $id)
                           ->with('success', '✅ Pembayaran berhasil! Top up telah ditambahkan ke akun game Anda.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengupload file');
        }
    }

    // ============= METHOD DETAIL =============
    public function detail($id)
    {
        // Cek login
        if (!session()->has('user_id')) {
            return redirect()->to('/user/login');
        }

        $userId = session()->get('user_id');
        
        // Ambil data transaksi
        $transaction = $this->transactionModel
            ->select('transactions.*, games.name as game_name, games.category, payment_methods.name as payment_name, payment_methods.type as payment_type')
            ->join('games', 'games.id = transactions.game_id', 'left')
            ->join('payment_methods', 'payment_methods.id = transactions.payment_method_id', 'left')
            ->where('transactions.id', $id)
            ->where('transactions.user_id', $userId)
            ->first();

        if (!$transaction) {
            return redirect()->to('/transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('transactions/detail', ['transaction' => $transaction]);
    }
}