<?php

namespace App\Models;

use CodeIgniter\Model;

class RegionModel extends Model
{
    protected $table         = 'region';
    protected $primaryKey    = 'id_region';
    protected $allowedFields = ['nombre'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;
}
