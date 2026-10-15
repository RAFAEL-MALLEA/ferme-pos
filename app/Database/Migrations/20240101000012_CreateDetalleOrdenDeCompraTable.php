<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * BUG ORIGINAL: "Cannot delete or update a parent row ... fk_id_producto_pk"
 * (49 veces) al intentar borrar un producto que ya estaba en una orden de
 * compra. Se corrige en el modelo con borrado logico de producto, nunca
 * DELETE fisico si existen detalles asociados.
 */
class CreateDetalleOrdenDeCompraTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detalle'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'orden_compra_fk' => ['type' => 'INT', 'unsigned' => true],
            'id_producto_pk'  => ['type' => 'INT', 'unsigned' => true],
            'cantidad'        => ['type' => 'INT'],
            'precio_unitario' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'subtotal'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
        ]);
        $this->forge->addPrimaryKey('id_detalle');
        $this->forge->addForeignKey('orden_compra_fk', 'orden_de_compra', 'id_orden_compra', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_producto_pk', 'producto', 'id_producto', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('detalle_orden_de_compra');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_orden_de_compra');
    }
}
