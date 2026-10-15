<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * BUG ORIGINAL: "Unknown column 'u.nom_usuario' in 'field list'" y
 * "Unknown column 'nivel_acceso' in 'field list'" -> el codigo consultaba
 * columnas que la tabla real no tenia. Aqui se dejan explicitas y
 * consistentes con lo que los controladores van a usar.
 *
 * Tambien: en vez de ON DELETE NO ACTION (que generaba los
 * "Cannot delete or update a parent row" al eliminar un usuario o su
 * ficha personal), se maneja con BORRADO LOGICO (columna estado) en el
 * modelo, nunca DELETE fisico sobre filas con relaciones.
 */
class CreateUsuarioTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usuario'      => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'rut_fk'          => ['type' => 'VARCHAR', 'constraint' => 12],
            'nom_usuario'     => ['type' => 'VARCHAR', 'constraint' => 50],
            'contrasena'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'nivel_acceso_fk' => ['type' => 'INT', 'unsigned' => true],
            'estado'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_usuario');
        $this->forge->addUniqueKey('nom_usuario');
        $this->forge->addForeignKey('rut_fk', 'datos_personales', 'rut', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('nivel_acceso_fk', 'nivel_acceso', 'id_nivel_acceso', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('usuario');
    }

    public function down()
    {
        $this->forge->dropTable('usuario');
    }
}
