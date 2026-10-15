<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVentaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_venta'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'usuario_fk'     => ['type' => 'INT', 'unsigned' => true],
            'cliente_rut_fk' => ['type' => 'VARCHAR', 'constraint' => 12, 'null' => true],
            'empresa_fk'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'fecha'          => ['type' => 'DATETIME'],
            'tipo_documento' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'boleta'], // boleta|factura
            'total'          => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'estado'         => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'completada'], // completada|anulada
        ]);
        $this->forge->addPrimaryKey('id_venta');
        $this->forge->addForeignKey('usuario_fk', 'usuario', 'id_usuario', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('cliente_rut_fk', 'datos_personales', 'rut', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('empresa_fk', 'empresa', 'id_empresa', 'CASCADE', 'SET NULL');
        $this->forge->createTable('venta');
    }

    public function down()
    {
        $this->forge->dropTable('venta');
    }
}
