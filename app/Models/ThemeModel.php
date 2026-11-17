<?php

namespace App\Models;

use CodeIgniter\Model;

class ThemeModel extends Model
{
    protected $table         = 'themes';
    protected $primaryKey    = 'key';
    protected $useAutoIncrement = false;
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['key', 'label', 'capacity', 'preview_image'];
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';

    /**
     * Get all themes as key => label associative array
     */
    public function getAllThemesAssoc(): array
    {
        $rows = $this->orderBy('label', 'ASC')->findAll();
        $result = [];
        foreach ($rows as $row) {
            $result[$row['key']] = $row['label'];
        }
        return $result;
    }
}
