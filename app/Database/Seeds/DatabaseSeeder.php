<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('App\Database\Seeds\NivelAccesoSeeder');
        $this->call('App\Database\Seeds\RegionComunaSeeder');
        $this->call('App\Database\Seeds\MonedaSeeder');
        $this->call('App\Database\Seeds\AdminUsuarioSeeder');
    }
}
