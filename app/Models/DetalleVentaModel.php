<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Nota: en el proyecto original el modelo se llamaba "detalleVentaModel"
 * (con minuscula inicial), inconsistente con el resto (UsuarioModel,
 * ProductosAdminModel, etc.) y contrario a PSR-4/convencion CI4. Aqui
 * se deja como DetalleVentaModel, en PascalCase como corresponde.
 */
class DetalleVentaModel extends Model
{
    protected $table         = 'detalle_venta';
    protected $primaryKey    = 'id_detalle_venta';
    protected $allowedFields = ['venta_fk', 'id_producto_fk', 'cantidad', 'precio_unitario', 'subtotal'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function porVenta(int $idVenta): array
    {
        return $this->select('detalle_venta.*, producto.nombre as producto_nombre')
            ->join('producto', 'producto.id_producto = detalle_venta.id_producto_fk')
            ->where('venta_fk', $idVenta)
            ->findAll();
    }
}
