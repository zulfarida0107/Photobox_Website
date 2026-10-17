<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetTokenToCustomers extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('customers');

        $toAdd = [];
        if (!in_array('reset_token', $fields)) {
            $toAdd['reset_token'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'status',
            ];
        }

        if (!in_array('reset_token_expires_at', $fields)) {
            $toAdd['reset_token_expires_at'] = [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'reset_token',
            ];
        }

        if (!empty($toAdd)) {
            $this->forge->addColumn('customers', $toAdd);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('customers');

        if (in_array('reset_token_expires_at', $fields)) {
            $this->forge->dropColumn('customers', 'reset_token_expires_at');
        }

        if (in_array('reset_token', $fields)) {
            $this->forge->dropColumn('customers', 'reset_token');
        }
    }
}
