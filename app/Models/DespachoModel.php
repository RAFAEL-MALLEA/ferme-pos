<?php

namespace App\Models;

use CodeIgniter\Model;

class DespachoModel extends Model
{
    protected $table         = 'despacho';
    protected $primaryKey    = 'id_despacho';
    protected $allowedFields = ['venta_fk', 'direccion_entrega', 'transportista', 'fecha_despacho', 'estado'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function listarConVenta(): array
    {
        return $this->select('despacho.*, venta.id_venta, venta.total')
            ->join('venta', 'venta.id_venta = despacho.venta_fk')
            ->orderBy('despacho.id_despacho', 'DESC')
            ->findAll();
    }
}
