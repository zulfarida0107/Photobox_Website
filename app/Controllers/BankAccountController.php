<?php

namespace App\Controllers;

use App\Models\BankAccountModel;
use CodeIgniter\Controller;

class BankAccountController extends Controller
{
    protected $bankModel;

    public function __construct()
    {
        $this->bankModel = new BankAccountModel();
    }

    // GET /bank-accounts — daftar rekening bank
    public function index()
    {
        $data['banks'] = $this->bankModel->findAll();
        return view('bank_accounts/index', $data);
    }

    // POST /store — simpan rekening baru ke DB
    public function store()
    {
        $data = [
            'bank_name'      => $this->request->getPost('bank_name'),
            'account_number' => $this->request->getPost('account_number'),
            'account_name'   => $this->request->getPost('account_name'),
        ];

        $this->bankModel->insert($data);
        return redirect()->to('/bank-accounts')->with('success', 'Rekening berhasil ditambahkan!');
    }

    // PUT /update/:id
    public function update($id)
    {
        $raw  = $this->request->getRawInput();
        $data = [
            'bank_name'      => $raw['bank_name'] ?? $this->request->getPost('bank_name'),
            'account_number' => $raw['account_number'] ?? $this->request->getPost('account_number'),
            'account_name'   => $raw['account_name'] ?? $this->request->getPost('account_name'),
        ];

        $this->bankModel->update($id, array_filter($data));
        return redirect()->to('/bank-accounts')->with('success', 'Rekening berhasil diupdate.');
    }

    // DELETE /delete/:id
    public function delete($id)
    {
        $this->bankModel->delete($id);
        return redirect()->to('/bank-accounts')->with('success', 'Rekening berhasil dihapus.');
    }
}
