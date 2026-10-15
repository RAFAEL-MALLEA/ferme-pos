<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BUG ORIGINAL: se llamaba a DatosPersonalesModel::last(), que no existe
 * en CI4 (eso es de Eloquent/Laravel, no de CodeIgniter). Y a
 * UsuarioModel::obtDatosPersonales() / ::DatosPersonales(), inexistentes.
 * Aqui se deja un solo metodo claro y reutilizable: obtenerPorRut().
 */
class DatosPersonalesModel extends Model
{
    protected $table         = 'datos_personales';
    protected $primaryKey    = 'rut';
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'rut', 'nombres', 'apellidos', 'natural_juridico',
        'direccion', 'telefono', 'email', 'comuna_fk', 'created_at',
    ];
    protected $returnType      = 'array';
    protected $useTimestamps   = false;

    protected $validationRules = [
        'rut'              => 'required|max_length[12]',
        'nombres'          => 'required|max_length[100]',
        'apellidos'        => 'required|max_length[100]',
        'natural_juridico' => 'required|in_list[N,J]',
        'comuna_fk'        => 'required|is_natural_no_zero',
    ];

    public function obtenerPorRut(string $rut): ?array
    {
        return $this->find($rut);
    }

    public function guardarFicha(array $datos): bool
    {
        $datos['created_at'] = $datos['created_at'] ?? date('Y-m-d H:i:s');

        if ($this->obtenerPorRut($datos['rut'])) {
            return (bool) $this->update($datos['rut'], $datos);
        }

        return (bool) $this->insert($datos);
    }
}
