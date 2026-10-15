<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdenCompraModel extends Model
{
    protected $table         = 'orden_de_compra';
    protected $primaryKey    = 'id_orden_compra';
    protected $allowedFields = ['proveedor_fk', 'usuario_fk', 'fecha', 'estado', 'total'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function listarConDetalle(): array
    {
        return $this->select('orden_de_compra.*, proveedor.razon_social, usuario.nom_usuario')
            ->join('proveedor', 'proveedor.id_proveedor = orden_de_compra.proveedor_fk')
            ->join('usuario', 'usuario.id_usuario = orden_de_compra.usuario_fk')
            ->orderBy('orden_de_compra.fecha', 'DESC')
            ->findAll();
    }
}
