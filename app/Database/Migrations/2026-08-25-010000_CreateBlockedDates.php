<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlockedDates extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'blocked_date' => [
                'type' => 'DATE',
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('blocked_date');
        $this->forge->createTable('blocked_dates');
    }

    public function down()
    {
        $this->forge->dropTable('blocked_dates');
    }
}
