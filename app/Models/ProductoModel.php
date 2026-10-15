<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BUG ORIGINAL en logs:
 *   - "Call to undefined method App\Models\ProductosAdminModel::eliminarProducto"
 *   - "Cannot delete or update a parent row ... fk_id_producto_pk" (DELETE fisico
 *     de un producto que ya tenia ventas/compras asociadas)
 *   - "Column 'fecha_vencimiento' cannot be null"
 *   - "Cannot add or update a child row ... fk_categoria"
 *
 * Correcciones: baja logica SIEMPRE (eliminar() nunca hace DELETE fisico),
 * fecha_vencimiento es opcional de verdad, y se valida la categoria antes
 * de guardar (ver ProductosAdmin::guardar en el controlador).
 */
class ProductoModel extends Model
{
    protected $table         = 'producto';
    protected $primaryKey    = 'id_producto';
    protected $allowedFields = [
        'nombre', 'descripcion', 'categoria', 'proveedor_fk', 'precio_compra',
        'precio_venta', 'stock', 'stock_minimo', 'fecha_vencimiento', 'imagen',
        'estado', 'created_at', 'updated_at',
    ];
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    protected $validationRules = [
        'nombre'        => 'required|max_length[150]',
        'categoria'     => 'required|is_natural_no_zero',
        'precio_venta'  => 'required|decimal',
        'stock'         => 'permit_empty|is_natural',
    ];

    public function catalogoActivo(): array
    {
        return $this->select('producto.*, categoria.nombre as categoria_nombre')
            ->join('categoria', 'categoria.id_categoria = producto.categoria')
            ->where('producto.estado', 1)
            ->orderBy('producto.nombre', 'ASC')
            ->findAll();
    }

    public function conStockBajo(): array
    {
        return $this->where('estado', 1)
            ->where('stock <=', 'stock_minimo', false)
            ->findAll();
    }

    /**
     * Baja logica: nunca hace DELETE fisico, por eso nunca va a chocar
     * con las FK de detalle_venta / detalle_orden_de_compra.
     */
    public function eliminarProducto(int $id): bool
    {
        return (bool) $this->update($id, ['estado' => 0]);
    }

    public function descontarStock(int $idProducto, int $cantidad): bool
    {
        $producto = $this->find($idProducto);
        if (! $producto) {
            return false;
        }

        $nuevoStock = max(0, $producto['stock'] - $cantidad);

        return (bool) $this->update($idProducto, ['stock' => $nuevoStock]);
    }

    public function aumentarStock(int $idProducto, int $cantidad): bool
    {
        $producto = $this->find($idProducto);
        if (! $producto) {
            return false;
        }

        return (bool) $this->update($idProducto, ['stock' => $producto['stock'] + $cantidad]);
    }
}
