<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MonedaSeeder extends Seeder
{
    public function run()
    {
        $existe = $this->db->table('moneda')->where('nombre', 'Peso Chileno')->get()->getRow();

        if (! $existe) {
            $this->db->table('moneda')->insert([
                'nombre'      => 'Peso Chileno',
                'simbolo'     => '$',
                'tasa_cambio' => 1,
                'por_defecto' => 1,
            ]);
        }
    }
}
