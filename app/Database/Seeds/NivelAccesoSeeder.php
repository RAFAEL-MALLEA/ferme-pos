<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NivelAccesoSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Administrador', 'Vendedor', 'Bodeguero'];

        foreach ($roles as $rol) {
            $existe = $this->db->table('nivel_acceso')->where('nombre', $rol)->get()->getRow();
            if (! $existe) {
                $this->db->table('nivel_acceso')->insert(['nombre' => $rol]);
            }
        }
    }
}
