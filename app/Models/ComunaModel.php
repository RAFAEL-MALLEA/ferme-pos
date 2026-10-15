<?php

namespace App\Models;

use CodeIgniter\Model;

class ComunaModel extends Model
{
    protected $table         = 'comuna';
    protected $primaryKey    = 'id_comuna';
    protected $allowedFields = ['nombre', 'region_fk'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function listarConRegion(): array
    {
        return $this->select('comuna.*, region.nombre as nombre_region')
            ->join('region', 'region.id_region = comuna.region_fk')
            ->orderBy('comuna.nombre', 'ASC')
            ->findAll();
    }
}
