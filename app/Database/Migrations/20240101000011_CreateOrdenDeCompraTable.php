<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdenDeCompraTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_orden_compra' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'proveedor_fk'    => ['type' => 'INT', 'unsigned' => true],
            'usuario_fk'      => ['type' => 'INT', 'unsigned' => true],
            'fecha'           => ['type' => 'DATETIME'],
            'estado'          => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pendiente'], // pendiente|recibida|anulada
            'total'           => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id_orden_compra');
        $this->forge->addForeignKey('proveedor_fk', 'proveedor', 'id_proveedor', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('usuario_fk', 'usuario', 'id_usuario', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('orden_de_compra');
    }

    public function down()
    {
        $this->forge->dropTable('orden_de_compra');
    }
}
