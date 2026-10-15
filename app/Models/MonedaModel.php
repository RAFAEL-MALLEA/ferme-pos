<?php

namespace App\Models;

use CodeIgniter\Model;

class MonedaModel extends Model
{
    protected $table         = 'moneda';
    protected $primaryKey    = 'id_moneda';
    protected $allowedFields = ['nombre', 'simbolo', 'tasa_cambio', 'por_defecto'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre'  => 'required|max_length[40]',
        'simbolo' => 'required|max_length[5]',
    ];

    public function obtenerPorDefecto(): ?array
    {
        return $this->where('por_defecto', 1)->first();
    }
}
