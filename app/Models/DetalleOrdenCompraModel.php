<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleOrdenCompraModel extends Model
{
    protected $table         = 'detalle_orden_de_compra';
    protected $primaryKey    = 'id_detalle';
    protected $allowedFields = ['orden_compra_fk', 'id_producto_pk', 'cantidad', 'precio_unitario', 'subtotal'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function porOrden(int $idOrdenCompra): array
    {
        return $this->select('detalle_orden_de_compra.*, producto.nombre as producto_nombre')
            ->join('producto', 'producto.id_producto = detalle_orden_de_compra.id_producto_pk')
            ->where('orden_compra_fk', $idOrdenCompra)
            ->findAll();
    }
}
