<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BUG ORIGINAL: 861 ocurrencias de
 *   "Too few arguments to function App\Controllers\Empresas::boolClienteEmpresa(),
 *    0 passed ... and exactly 1 expected"
 * boolClienteEmpresa() es basicamente "dado un RUT/id, dime si existe como
 * cliente empresa". Aqui ese chequeo vive en el MODELO (es una consulta,
 * no logica de controlador), y el controlador simplemente delega -
 * ver Empresas::boolClienteEmpresa($id) en el controlador, que SIEMPRE
 * recibe el id porque la ruta lo exige (ver Routes.php).
 */
class EmpresaModel extends Model
{
    protected $table         = 'empresa';
    protected $primaryKey    = 'id_empresa';
    protected $allowedFields = ['razon_social', 'rut', 'giro', 'direccion', 'telefono', 'email', 'estado'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $validationRules = [
        'razon_social' => 'required|max_length[120]',
        'rut'          => 'required|max_length[12]|is_unique[empresa.rut,id_empresa,{id_empresa}]',
    ];

    public function activas(): array
    {
        return $this->where('estado', 1)->orderBy('razon_social', 'ASC')->findAll();
    }

    public function esClienteEmpresa(int $idEmpresa): bool
    {
        $empresa = $this->where('estado', 1)->find($idEmpresa);

        return $empresa !== null;
    }

    public function darDeBaja(int $id): bool
    {
        return (bool) $this->update($id, ['estado' => 0]);
    }
}
