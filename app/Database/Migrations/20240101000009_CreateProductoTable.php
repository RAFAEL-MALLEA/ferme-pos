<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * BUG ORIGINAL: "Column 'fecha_vencimiento' cannot be null" (46 veces) y
 * "Cannot add or update a child row ... fk_categoria" (producto sin
 * categoria valida). fecha_vencimiento ahora es NULLABLE de verdad
 * (una ferreteria vende muchos productos sin vencimiento: tornillos,
 * herramientas, etc.), y categoria queda obligatoria pero validada en
 * el controlador ANTES del insert.
 */
class CreateProductoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_producto'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'             => ['type' => 'VARCHAR', 'constraint' => 150],
            'descripcion'        => ['type' => 'TEXT', 'null' => true],
            'categoria'          => ['type' => 'INT', 'unsigned' => true],
            'proveedor_fk'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'precio_compra'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'precio_venta'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'stock'              => ['type' => 'INT', 'default' => 0],
            'stock_minimo'       => ['type' => 'INT', 'default' => 5],
            'fecha_vencimiento'  => ['type' => 'DATE', 'null' => true],
            'imagen'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'estado'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_producto');
        $this->forge->addForeignKey('categoria', 'categoria', 'id_categoria', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('proveedor_fk', 'proveedor', 'id_proveedor', 'CASCADE', 'SET NULL');
        $this->forge->createTable('producto');
    }

    public function down()
    {
        $this->forge->dropTable('producto');
    }
}
