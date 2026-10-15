<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Columnas confirmadas por logs del proyecto original:
 *   rut (PK), comuna_fk (NOT NULL - rompia con "cannot be null"),
 *   natural_juridico (NOT NULL - rompia igual).
 * El resto se reconstruye como lo minimo razonable para una ficha de
 * cliente/trabajador de ferreteria.
 */
class CreateDatosPersonalesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'rut'              => ['type' => 'VARCHAR', 'constraint' => 12], // sin puntos, con guion y DV
            'nombres'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellidos'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'natural_juridico' => ['type' => 'ENUM', 'constraint' => ['N', 'J'], 'default' => 'N'],
            'direccion'        => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'telefono'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'comuna_fk'        => ['type' => 'INT', 'unsigned' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('rut');
        $this->forge->addForeignKey('comuna_fk', 'comuna', 'id_comuna', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('datos_personales');
    }

    public function down()
    {
        $this->forge->dropTable('datos_personales');
    }
}
