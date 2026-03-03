<?php

namespace App\Controllers;

use App\Models\GameModel;
use CodeIgniter\Controller;

class GameController extends Controller
{
    protected $gameModel;

    public function __construct()
    {
        $this->gameModel = new GameModel();
    }

    // GET /games — halaman home, tampilkan semua game
    public function index()
    {
        $data['games'] = $this->gameModel->findAll();
        return view('games/index', $data);
    }

    // GET /games/create — form tambah game
    public function create()
    {
        return view('games/create');
    }

    // POST /games/store — simpan game baru ke DB
    public function store()
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'category'    => $this->request->getPost('category'),
            'image'       => $this->request->getPost('image'),
        ];

        $this->gameModel->insert($data);
        return redirect()->to('/games')->with('success', 'Game berhasil ditambahkan!');
    }

    // PUT /games/update/:id — update data game
    public function update($id)
    {
        $data = [
            'name'        => $this->request->getPost('name') ?? $this->request->getRawInput()['name'] ?? null,
            'description' => $this->request->getPost('description') ?? $this->request->getRawInput()['description'] ?? null,
            'category'    => $this->request->getPost('category') ?? $this->request->getRawInput()['category'] ?? null,
            'image'       => $this->request->getPost('image') ?? $this->request->getRawInput()['image'] ?? null,
        ];

        $data = array_filter($data); // hapus yang null
        $this->gameModel->update($id, $data);
        return redirect()->to('/games')->with('success', 'Game berhasil diupdate!');
    }

    // DELETE /games/delete/:id
    public function delete($id)
    {
        $this->gameModel->delete($id);
        return redirect()->to('/games')->with('success', 'Game berhasil dihapus.');
    }
}
