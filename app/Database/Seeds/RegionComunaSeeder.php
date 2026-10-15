<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Set minimo de Region de Valparaiso para poder operar el sistema de
 * inmediato. Agrega mas regiones/comunas desde el modulo Configuracion
 * o directo en la tabla segun lo necesites.
 */
class RegionComunaSeeder extends Seeder
{
    public function run()
    {
        $existente = $this->db->table('region')->where('nombre', 'Valparaíso')->get()->getRow();

        if (! $existente) {
            $this->db->table('region')->insert(['nombre' => 'Valparaíso']);
            $idRegion = $this->db->insertID();

            $comunas = ['Viña del Mar', 'Valparaíso', 'Quilpué', 'Villa Alemana', 'Concón'];
            foreach ($comunas as $comuna) {
                $this->db->table('comuna')->insert([
                    'nombre'    => $comuna,
                    'region_fk' => $idRegion,
                ]);
            }
        }
    }
}
