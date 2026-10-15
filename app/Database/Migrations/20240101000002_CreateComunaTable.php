<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateComunaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_comuna' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'region_fk' => ['type' => 'INT', 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey('id_comuna');
        $this->forge->addForeignKey('region_fk', 'region', 'id_region', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('comuna');
    }

    public function down()
    {
        $this->forge->dropTable('comuna');
    }
}
