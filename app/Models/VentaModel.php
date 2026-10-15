<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table         = 'venta';
    protected $primaryKey    = 'id_venta';
    protected $allowedFields = [
        'usuario_fk', 'cliente_rut_fk', 'empresa_fk', 'fecha',
        'tipo_documento', 'total', 'estado',
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function listarConDetalle(): array
    {
        return $this->select('venta.*, usuario.nom_usuario')
            ->join('usuario', 'usuario.id_usuario = venta.usuario_fk')
            ->orderBy('venta.fecha', 'DESC')
            ->findAll();
    }
}
