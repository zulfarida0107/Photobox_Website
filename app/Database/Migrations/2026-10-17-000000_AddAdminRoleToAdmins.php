<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRoleToAdmins extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('admins') && $this->db->fieldExists('role', 'admins')) {
            $this->forge->modifyColumn('admins', [
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['superadmin', 'admin', 'staff'],
                    'default'    => 'staff',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('admins') && $this->db->fieldExists('role', 'admins')) {
            $this->db->table('admins')
                ->where('role', 'admin')
                ->update(['role' => 'staff']);

            $this->forge->modifyColumn('admins', [
                'role' => [
                    'type'       => 'ENUM',
                    'constraint' => ['superadmin', 'staff'],
                    'default'    => 'staff',
                ],
            ]);
        }
    }
}
