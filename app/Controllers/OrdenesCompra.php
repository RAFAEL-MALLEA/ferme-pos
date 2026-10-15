<?php

namespace App\Controllers;

use App\Models\DetalleOrdenCompraModel;
use App\Models\OrdenCompraModel;
use App\Models\ProductoModel;
use App\Models\ProveedorModel;

class OrdenesCompra extends BaseController
{
    public function index()
    {
        return view('compras/index', [
            'ordenes' => (new OrdenCompraModel())->listarConDetalle(),
        ]);
    }

    public function nueva()
    {
        return view('compras/form', [
            'proveedores' => (new ProveedorModel())->activos(),
            'productos'   => (new ProductoModel())->catalogoActivo(),
        ]);
    }

    public function guardar()
    {
        $proveedorFk = $this->request->getPost('proveedor_fk');
        $productos   = $this->request->getPost('producto') ?? [];   // ids
        $cantidades  = $this->request->getPost('cantidad') ?? [];   // cantidades paralelas

        if (empty($proveedorFk) || empty($productos)) {
            return redirect()->back()->withInput()->with('error', 'Selecciona proveedor y al menos un producto.');
        }

        $productoModel = new ProductoModel();
        $total         = 0;
        $detalles      = [];

        foreach ($productos as $i => $idProducto) {
            $cantidad = max(1, (int) ($cantidades[$i] ?? 1));
            $producto = $productoModel->find($idProducto);

            if (! $producto) {
                continue;
            }

            $subtotal   = $producto['precio_compra'] * $cantidad;
            $total     += $subtotal;
            $detalles[] = [
                'id_producto_pk'  => $idProducto,
                'cantidad'        => $cantidad,
                'precio_unitario' => $producto['precio_compra'],
                'subtotal'        => $subtotal,
            ];
        }

        if (empty($detalles)) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la orden, revisa los productos.');
        }

        $db = db_connect();
        $db->transStart();

        $ordenModel = new OrdenCompraModel();
        $idOrden    = $ordenModel->insert([
            'proveedor_fk' => $proveedorFk,
            'usuario_fk'   => $this->usuarioId(),
            'fecha'        => date('Y-m-d H:i:s'),
            'estado'       => 'pendiente',
            'total'        => $total,
        ], true);

        $detalleModel = new DetalleOrdenCompraModel();
        foreach ($detalles as $detalle) {
            $detalle['orden_compra_fk'] = $idOrden;
            $detalleModel->insert($detalle);
        }

        $db->transComplete();

        return redirect()->to('/compras')->with('mensaje', 'Orden de compra creada.');
    }

    public function ver(int $id)
    {
        $orden = (new OrdenCompraModel())->find($id);

        if (! $orden) {
            return redirect()->to('/compras')->with('error', 'Orden no encontrada.');
        }

        return view('compras/ver', [
            'orden'    => $orden,
            'detalles' => (new DetalleOrdenCompraModel())->porOrden($id),
        ]);
    }

    /**
     * Marca la orden como recibida y SUMA el stock de cada producto.
     * Esto era lo que faltaba en el original: las ordenes de compra no
     * tenian un flujo claro de "llego la mercaderia", por eso el stock
     * se desincronizaba.
     */
    public function recibir(int $id)
    {
        $ordenModel = new OrdenCompraModel();
        $orden      = $ordenModel->find($id);

        if (! $orden || $orden['estado'] !== 'pendiente') {
            return redirect()->to('/compras')->with('error', 'Esta orden ya fue procesada.');
        }

        $detalles      = (new DetalleOrdenCompraModel())->porOrden($id);
        $productoModel = new ProductoModel();

        foreach ($detalles as $detalle) {
            $productoModel->aumentarStock($detalle['id_producto_pk'], $detalle['cantidad']);
        }

        $ordenModel->update($id, ['estado' => 'recibida']);

        return redirect()->to('/compras')->with('mensaje', 'Orden marcada como recibida y stock actualizado.');
    }

    public function eliminar(int $id)
    {
        $ordenModel = new OrdenCompraModel();
        $orden      = $ordenModel->find($id);

        if ($orden && $orden['estado'] === 'pendiente') {
            $ordenModel->update($id, ['estado' => 'anulada']);
        }

        return redirect()->to('/compras')->with('mensaje', 'Orden anulada.');
    }
}
