<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Crea un usuario administrador inicial para poder entrar al sistema
 * la primera vez. CAMBIA LA CONTRASEÑA apenas inicies sesion.
 *
 * Usuario:    admin
 * Contrasena: Admin#2024
 */
class AdminUsuarioSeeder extends Seeder
{
    public function run()
    {
        $rut = '11111111-1';

        $existeFicha = $this->db->table('datos_personales')->where('rut', $rut)->get()->getRow();
        if (! $existeFicha) {
            $comuna = $this->db->table('comuna')->get()->getRow();

            $this->db->table('datos_personales')->insert([
                'rut'              => $rut,
                'nombres'          => 'Administrador',
                'apellidos'        => 'Sistema',
                'natural_juridico' => 'N',
                'comuna_fk'        => $comuna->id_comuna ?? 1,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        }

        $existeUsuario = $this->db->table('usuario')->where('nom_usuario', 'admin')->get()->getRow();
        if (! $existeUsuario) {
            $rolAdmin = $this->db->table('nivel_acceso')->where('nombre', 'Administrador')->get()->getRow();

            $this->db->table('usuario')->insert([
                'rut_fk'          => $rut,
                'nom_usuario'     => 'admin',
                'contrasena'      => password_hash('Admin#2024', PASSWORD_DEFAULT),
                'nivel_acceso_fk' => $rolAdmin->id_nivel_acceso ?? 1,
                'estado'          => 1,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
