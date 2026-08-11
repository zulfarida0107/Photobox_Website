<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ThemeModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ThemeSeeder extends Seeder
{
    public function run()
    {
        $themeModel = new ThemeModel();
        
        // Only seed if table is empty
        if ($themeModel->countAllResults() > 0) {
            return;
        }

        $defaults = [
            ['key' => 'retro',   'label' => 'Retro'],
            ['key' => 'mrt',     'label' => 'MRT'],
            ['key' => 'vintage', 'label' => 'Vintage'],
            ['key' => 'modern',  'label' => 'Modern'],
            ['key' => 'neon',    'label' => 'Neon'],
            ['key' => 'rustic',  'label' => 'Rustic'],
            ['key' => 'classic', 'label' => 'Classic'],
        ];

        // Fetch custom themes from existing locations
        $db = \Config\Database::connect();
        
        try {
            $builder = $db->table('locations');
            $locations = $builder->select('themes_available')->get()->getResultArray();
            
            $existingKeys = array_column($defaults, 'key');

            foreach ($locations as $loc) {
                if (!empty($loc['themes_available'])) {
                    $themes = json_decode($loc['themes_available'], true);
                    if (is_array($themes)) {
                        foreach ($themes as $theme) {
                            $key = strtolower(preg_replace('/[^a-z0-9]/i', '_', $theme));
                            $key = preg_replace('/_+/', '_', trim($key, '_'));
                            
                            if (!in_array($key, $existingKeys)) {
                                $defaults[] = [
                                    'key' => $key,
                                    'label' => $theme
                                ];
                                $existingKeys[] = $key;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // locations table might not exist yet if running all migrations
            // just continue with defaults
        }

        // Add timestamps
        $now = date('Y-m-d H:i:s');
        foreach ($defaults as &$theme) {
            $theme['created_at'] = $now;
            $theme['updated_at'] = $now;
        }

        $themeModel->insertBatch($defaults);
    }
}
