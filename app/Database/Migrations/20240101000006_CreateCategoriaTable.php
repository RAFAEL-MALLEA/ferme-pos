<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_categoria' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 80],
            'estado'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addPrimaryKey('id_categoria');
        $this->forge->addUniqueKey('nombre');
        $this->forge->createTable('categoria');
    }

    public function down()
    {
        $this->forge->dropTable('categoria');
    }
}
