<?php

namespace App\Controllers;

use App\Models\ProductoModel;

/**
 * Catalogo de productos de solo lectura (lo usa el Punto de Venta para
 * buscar productos al armar el carrito). La administracion real
 * (crear/editar/eliminar) vive en ProductosAdmin.
 */
class Productos extends BaseController
{
    public function index()
    {
        $model = new ProductoModel();

        return view('productos/catalogo', [
            'productos' => $model->catalogoActivo(),
        ]);
    }

    public function listarJson()
    {
        $model    = new ProductoModel();
        $busqueda = $this->request->getGet('q') ?? '';

        $productos = $model->select('producto.*, categoria.nombre as categoria_nombre')
            ->join('categoria', 'categoria.id_categoria = producto.categoria')
            ->where('producto.estado', 1)
            ->groupStart()
                ->like('producto.nombre', $busqueda)
                ->orLike('producto.descripcion', $busqueda)
            ->groupEnd()
            ->findAll();

        return $this->respuestaJson(true, $productos);
    }
}
