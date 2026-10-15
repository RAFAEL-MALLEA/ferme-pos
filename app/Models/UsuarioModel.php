<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BUG ORIGINAL en logs:
 *   - "Call to undefined method App\Models\UsuarioModel::obtDatosPersonales"
 *   - "Call to undefined method App\Models\UsuarioModel::DatosPersonales"
 *   - "Unknown column 'u.nom_usuario' / 'nivel_acceso' in field list"
 *   - 54x "Too few arguments ... buscarPorRutJson(), 0 passed ... exactly 1 expected"
 *
 * Correcciones:
 *   1) Un solo metodo buscarPorRut($rut) con parametro SIEMPRE exigido
 *      por la ruta (ver Routes.php: usuarios/buscar-rut/(:segment)).
 *   2) JOIN explicito con alias y nombres de columna que SI existen
 *      (ver migracion CreateUsuarioTable).
 *   3) Nunca se hace DELETE fisico de un usuario (rompia FK con ventas,
 *      ordenes de compra, etc.) -> se usa baja logica (estado = 0).
 */
class UsuarioModel extends Model
{
    protected $table         = 'usuario';
    protected $primaryKey    = 'id_usuario';
    protected $allowedFields = [
        'rut_fk', 'nom_usuario', 'contrasena', 'nivel_acceso_fk',
        'estado', 'created_at', 'updated_at',
    ];
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $dateFormat     = 'datetime';

    protected $validationRules = [
        'rut_fk'          => 'required',
        'nom_usuario'     => 'required|min_length[4]|max_length[50]|is_unique[usuario.nom_usuario,id_usuario,{id_usuario}]',
        'nivel_acceso_fk' => 'required|is_natural_no_zero',
    ];

    public function listarConDetalle(): array
    {
        return $this->select('usuario.id_usuario, usuario.nom_usuario, usuario.estado,
                               datos_personales.nombres, datos_personales.apellidos, datos_personales.rut,
                               nivel_acceso.nombre as nivel_acceso_nombre')
            ->join('datos_personales', 'datos_personales.rut = usuario.rut_fk')
            ->join('nivel_acceso', 'nivel_acceso.id_nivel_acceso = usuario.nivel_acceso_fk')
            ->orderBy('usuario.id_usuario', 'DESC')
            ->findAll();
    }

    public function buscarPorRut(string $rut): ?array
    {
        return $this->select('usuario.*, datos_personales.nombres, datos_personales.apellidos')
            ->join('datos_personales', 'datos_personales.rut = usuario.rut_fk')
            ->where('datos_personales.rut', $rut)
            ->first();
    }

    public function obtenerParaLogin(string $nomUsuario): ?array
    {
        return $this->select('usuario.*, nivel_acceso.nombre as nivel_acceso_nombre, datos_personales.nombres')
            ->join('nivel_acceso', 'nivel_acceso.id_nivel_acceso = usuario.nivel_acceso_fk')
            ->join('datos_personales', 'datos_personales.rut = usuario.rut_fk')
            ->where('usuario.nom_usuario', $nomUsuario)
            ->where('usuario.estado', 1)
            ->first();
    }

    public function darDeBaja(int $idUsuario): bool
    {
        return (bool) $this->update($idUsuario, ['estado' => 0]);
    }
}
