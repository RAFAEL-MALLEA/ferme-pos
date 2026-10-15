<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetalleVentaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detalle_venta' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'venta_fk'         => ['type' => 'INT', 'unsigned' => true],
            'id_producto_fk'   => ['type' => 'INT', 'unsigned' => true],
            'cantidad'         => ['type' => 'INT'],
            'precio_unitario'  => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'subtotal'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addPrimaryKey('id_detalle_venta');
        $this->forge->addForeignKey('venta_fk', 'venta', 'id_venta', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_producto_fk', 'producto', 'id_producto', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('detalle_venta');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_venta');
    }
}
