<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDespachoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_despacho'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'venta_fk'          => ['type' => 'INT', 'unsigned' => true],
            'direccion_entrega' => ['type' => 'VARCHAR', 'constraint' => 150],
            'transportista'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'fecha_despacho'    => ['type' => 'DATE', 'null' => true],
            'estado'            => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pendiente'], // pendiente|en_ruta|entregado
        ]);
        $this->forge->addPrimaryKey('id_despacho');
        $this->forge->addForeignKey('venta_fk', 'venta', 'id_venta', 'CASCADE', 'CASCADE');
        $this->forge->createTable('despacho');
    }

    public function down()
    {
        $this->forge->dropTable('despacho');
    }
}
