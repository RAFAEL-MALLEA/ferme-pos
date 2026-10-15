<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNivelAccesoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_nivel_acceso' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'          => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addPrimaryKey('id_nivel_acceso');
        $this->forge->addUniqueKey('nombre');
        $this->forge->createTable('nivel_acceso');
    }

    public function down()
    {
        $this->forge->dropTable('nivel_acceso');
    }
}
