<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table         = 'proveedor';
    protected $primaryKey    = 'id_proveedor';
    protected $allowedFields = ['razon_social', 'rut', 'telefono', 'email', 'direccion', 'estado'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'razon_social' => 'required|max_length[120]',
        'rut'          => 'required|max_length[12]|is_unique[proveedor.rut,id_proveedor,{id_proveedor}]',
    ];

    public function activos(): array
    {
        return $this->where('estado', 1)->orderBy('razon_social', 'ASC')->findAll();
    }

    public function darDeBaja(int $id): bool
    {
        return (bool) $this->update($id, ['estado' => 0]);
    }
}
