<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMonedaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_moneda'    => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'simbolo'      => ['type' => 'VARCHAR', 'constraint' => 5],
            'tasa_cambio'  => ['type' => 'DECIMAL', 'constraint' => '10,4', 'default' => 1],
            'por_defecto'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id_moneda');
        $this->forge->createTable('moneda');
    }

    public function down()
    {
        $this->forge->dropTable('moneda');
    }
}
