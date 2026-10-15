<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BUG ORIGINAL: se llamaba a NivelAccesoModel::insertarNvl(), que nunca
 * existio ("Call to undefined method"). Con $allowedFields + insert()
 * del Model base de CI4 no hace falta un metodo custom para algo tan simple.
 */
class NivelAccesoModel extends Model
{
    protected $table            = 'nivel_acceso';
    protected $primaryKey       = 'id_nivel_acceso';
    protected $allowedFields    = ['nombre'];
    protected $returnType       = 'array';
    protected $useTimestamps    = false;
    protected $validationRules  = [
        'nombre' => 'required|min_length[3]|max_length[50]|is_unique[nivel_acceso.nombre,id_nivel_acceso,{id_nivel_acceso}]',
    ];
}
