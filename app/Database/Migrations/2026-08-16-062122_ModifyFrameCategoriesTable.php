<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyFrameCategoriesTable extends Migration
{
    public function up()
    {
        // 1. Alter category column in frames to VARCHAR(50) temporarily to allow migrating values
        $this->db->query("ALTER TABLE frames MODIFY COLUMN category VARCHAR(50)");

        // 2. Update existing records in frames to use capitalized categories
        $this->db->query("UPDATE frames SET category = 'Pastel' WHERE category = 'pastel'");
        $this->db->query("UPDATE frames SET category = 'Cute' WHERE category = 'cute'");
        $this->db->query("UPDATE frames SET category = 'Minimalist' WHERE category = 'minimalist' OR category = 'layout'");
        $this->db->query("UPDATE frames SET category = 'Newspaper' WHERE category = 'newspaper'");
        $this->db->query("UPDATE frames SET category = 'Magazine' WHERE category = 'magazine'");
        $this->db->query("UPDATE frames SET category = 'Retro 90s' WHERE category = 'retro 90s'");
        $this->db->query("UPDATE frames SET category = 'Y2k' WHERE category = 'y2k'");

        // 3. Alter category column back to new ENUM with capitalized values and no layout
        $this->db->query("ALTER TABLE frames MODIFY COLUMN category ENUM('Pastel', 'Cute', 'Minimalist', 'Newspaper', 'Magazine', 'Retro 90s', 'Y2k') DEFAULT 'Minimalist'");

        // 4. Clean up layout category from frame_categories table
        $this->db->table('frame_categories')->where('id', 'cat_layout')->delete();
        $this->db->table('frame_categories')->where('name', 'layout')->delete();

        // 5. Capitalize existing names in frame_categories table
        $this->db->query("UPDATE frame_categories SET name = 'Pastel' WHERE name = 'pastel'");
        $this->db->query("UPDATE frame_categories SET name = 'Cute' WHERE name = 'cute'");
        $this->db->query("UPDATE frame_categories SET name = 'Minimalist' WHERE name = 'minimalist'");
        $this->db->query("UPDATE frame_categories SET name = 'Newspaper' WHERE name = 'newspaper'");
        $this->db->query("UPDATE frame_categories SET name = 'Magazine' WHERE name = 'magazine'");
        $this->db->query("UPDATE frame_categories SET name = 'Retro 90s' WHERE name = 'retro 90s'");
        $this->db->query("UPDATE frame_categories SET name = 'Y2k' WHERE name = 'y2k'");
    }

    public function down()
    {
        // Revert 1: Alter category column to VARCHAR(50) temporarily
        $this->db->query("ALTER TABLE frames MODIFY COLUMN category VARCHAR(50)");

        // Revert 2: Update records back to lowercase
        $this->db->query("UPDATE frames SET category = 'pastel' WHERE category = 'Pastel'");
        $this->db->query("UPDATE frames SET category = 'cute' WHERE category = 'Cute'");
        $this->db->query("UPDATE frames SET category = 'minimalist' WHERE category = 'Minimalist'");
        $this->db->query("UPDATE frames SET category = 'newspaper' WHERE category = 'Newspaper'");
        $this->db->query("UPDATE frames SET category = 'magazine' WHERE category = 'Magazine'");
        $this->db->query("UPDATE frames SET category = 'retro 90s' WHERE category = 'Retro 90s'");
        $this->db->query("UPDATE frames SET category = 'y2k' WHERE category = 'Y2k'");

        // Revert 3: Alter category column back to old ENUM
        $this->db->query("ALTER TABLE frames MODIFY COLUMN category ENUM('pastel', 'cute', 'layout', 'minimalist', 'newspaper', 'magazine', 'retro 90s', 'y2k') DEFAULT 'minimalist'");

        // Revert 4: Put layout category back into frame_categories table
        $now = date('Y-m-d H:i:s');
        $this->db->table('frame_categories')->insert([
            'id'         => 'cat_layout',
            'name'       => 'layout',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Revert 5: Lowercase names in frame_categories table
        $this->db->query("UPDATE frame_categories SET name = 'pastel' WHERE name = 'Pastel'");
        $this->db->query("UPDATE frame_categories SET name = 'cute' WHERE name = 'Cute'");
        $this->db->query("UPDATE frame_categories SET name = 'minimalist' WHERE name = 'Minimalist'");
        $this->db->query("UPDATE frame_categories SET name = 'newspaper' WHERE name = 'Newspaper'");
        $this->db->query("UPDATE frame_categories SET name = 'magazine' WHERE name = 'Magazine'");
        $this->db->query("UPDATE frame_categories SET name = 'retro 90s' WHERE name = 'Retro 90s'");
        $this->db->query("UPDATE frame_categories SET name = 'y2k' WHERE name = 'Y2k'");
    }
}
