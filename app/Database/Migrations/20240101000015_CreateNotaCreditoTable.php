<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotaCreditoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_nota_credito' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'venta_fk'        => ['type' => 'INT', 'unsigned' => true],
            'usuario_fk'      => ['type' => 'INT', 'unsigned' => true],
            'motivo'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'monto'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'fecha'           => ['type' => 'DATETIME'],
        ]);
        $this->forge->addPrimaryKey('id_nota_credito');
        $this->forge->addForeignKey('venta_fk', 'venta', 'id_venta', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('usuario_fk', 'usuario', 'id_usuario', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('nota_credito');
    }

    public function down()
    {
        $this->forge->dropTable('nota_credito');
    }
}
