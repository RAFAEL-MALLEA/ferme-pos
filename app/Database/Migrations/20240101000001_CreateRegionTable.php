<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_region' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nombre'    => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addPrimaryKey('id_region');
        $this->forge->createTable('region');
    }

    public function down()
    {
        $this->forge->dropTable('region');
    }
}
