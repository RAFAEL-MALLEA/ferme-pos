<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table         = 'categoria';
    protected $primaryKey    = 'id_categoria';
    protected $allowedFields = ['nombre', 'estado'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[80]|is_unique[categoria.nombre,id_categoria,{id_categoria}]',
    ];

    public function activas(): array
    {
        return $this->where('estado', 1)->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Baja logica en vez de DELETE: una categoria con productos asociados
     * rompia por FK en el proyecto original. Esto nunca falla por FK.
     */
    public function darDeBaja(int $id): bool
    {
        return (bool) $this->update($id, ['estado' => 0]);
    }
}
