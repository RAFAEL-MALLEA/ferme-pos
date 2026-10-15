<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProveedorTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_proveedor' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'razon_social' => ['type' => 'VARCHAR', 'constraint' => 120],
            'rut'          => ['type' => 'VARCHAR', 'constraint' => 12],
            'telefono'     => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'direccion'    => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'estado'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id_proveedor');
        $this->forge->addUniqueKey('rut');
        $this->forge->createTable('proveedor');
    }

    public function down()
    {
        $this->forge->dropTable('proveedor');
    }
}
