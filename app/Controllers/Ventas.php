<?php

namespace App\Controllers;

use App\Models\DetalleVentaModel;
use App\Models\EmpresaModel;
use App\Models\ProductoModel;
use App\Models\VentaModel;

/**
 * Punto de venta. BUG ORIGINAL relacionado:
 *   - "Undefined property: App\Controllers\Ventas::$input" -> corregido en
 *     BaseController (parent::initController deja $this->request listo).
 *   - "Deletes are not allowed unless they contain a where or like clause."
 *     -> aqui jamas se hace un delete masivo; vaciarCarrito limpia solo la
 *     sesion del usuario actual, no toca la BD.
 * El carrito se guarda en SESION (no en BD), por eso no existen ya
 * Canasta/DetalleCarrito/TemporalCompra como controladores aparte: esa
 * separacion en 3 controladores era la causa de que el estado del carrito
 * se desincronizara entre pantallas en el proyecto original.
 */
class Ventas extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $empresaModel  = new EmpresaModel();

        return view('ventas/pos', [
            'productos' => $productoModel->catalogoActivo(),
            'empresas'  => $empresaModel->activas(),
            'carrito'   => $this->session->get('carrito') ?? [],
        ]);
    }

    public function agregarCarrito()
    {
        $idProducto = (int) $this->request->getPost('id_producto');
        $cantidad   = max(1, (int) $this->request->getPost('cantidad'));

        $producto = (new ProductoModel())->find($idProducto);

        if (! $producto || $producto['estado'] != 1) {
            return $this->respuestaJson(false, null, 'Producto no disponible.');
        }

        if ($producto['stock'] < $cantidad) {
            return $this->respuestaJson(false, null, 'Stock insuficiente. Disponible: ' . $producto['stock']);
        }

        $carrito = $this->session->get('carrito') ?? [];

        if (isset($carrito[$idProducto])) {
            $carrito[$idProducto]['cantidad'] += $cantidad;
        } else {
            $carrito[$idProducto] = [
                'id_producto' => $idProducto,
                'nombre'      => $producto['nombre'],
                'precio'      => $producto['precio_venta'],
                'cantidad'    => $cantidad,
            ];
        }

        $this->session->set('carrito', $carrito);

        return $this->respuestaJson(true, ['carrito' => $carrito]);
    }

    public function quitarCarrito(int $idProducto)
    {
        $carrito = $this->session->get('carrito') ?? [];
        unset($carrito[$idProducto]);
        $this->session->set('carrito', $carrito);

        return $this->respuestaJson(true, ['carrito' => $carrito]);
    }

    public function vaciarCarrito()
    {
        $this->session->remove('carrito');

        return $this->respuestaJson(true);
    }

    public function finalizar()
    {
        $carrito = $this->session->get('carrito') ?? [];

        if (empty($carrito)) {
            return redirect()->back()->with('error', 'El carrito está vacío.');
        }

        $productoModel     = new ProductoModel();
        $ventaModel        = new VentaModel();
        $detalleVentaModel = new DetalleVentaModel();

        // Revalidar stock de TODO el carrito antes de confirmar (evita
        // vender sobre stock negativo si dos vendedores venden a la vez).
        foreach ($carrito as $item) {
            $producto = $productoModel->find($item['id_producto']);
            if (! $producto || $producto['stock'] < $item['cantidad']) {
                return redirect()->back()->with('error', 'Stock insuficiente para "' . $item['nombre'] . '". Revisa el carrito.');
            }
        }

        $total = array_sum(array_map(fn ($i) => $i['precio'] * $i['cantidad'], $carrito));

        $db = db_connect();
        $db->transStart();

        $idVenta = $ventaModel->insert([
            'usuario_fk'     => $this->usuarioId(),
            'cliente_rut_fk' => $this->request->getPost('cliente_rut_fk') ?: null,
            'empresa_fk'     => $this->request->getPost('empresa_fk') ?: null,
            'fecha'          => date('Y-m-d H:i:s'),
            'tipo_documento' => $this->request->getPost('tipo_documento') ?: 'boleta',
            'total'          => $total,
            'estado'         => 'completada',
        ], true);

        foreach ($carrito as $item) {
            $detalleVentaModel->insert([
                'venta_fk'        => $idVenta,
                'id_producto_fk'  => $item['id_producto'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio'],
                'subtotal'        => $item['precio'] * $item['cantidad'],
            ]);

            $productoModel->descontarStock($item['id_producto'], $item['cantidad']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'No se pudo completar la venta, intenta nuevamente.');
        }

        $direccionDespacho = $this->request->getPost('direccion_despacho');
        if (! empty($direccionDespacho)) {
            (new \App\Models\DespachoModel())->insert([
                'venta_fk'          => $idVenta,
                'direccion_entrega' => $direccionDespacho,
                'estado'            => 'pendiente',
            ]);
        }

        $this->session->remove('carrito');

        return redirect()->to('/ventas/ver/' . $idVenta)->with('mensaje', 'Venta registrada correctamente.');
    }

    public function historial()
    {
        return view('ventas/historial', [
            'ventas' => (new VentaModel())->listarConDetalle(),
        ]);
    }

    public function ver(int $id)
    {
        $venta = (new VentaModel())->find($id);

        if (! $venta) {
            return redirect()->to('/ventas/historial')->with('error', 'Venta no encontrada.');
        }

        return view('ventas/detalle', [
            'venta'    => $venta,
            'detalles' => (new DetalleVentaModel())->porVenta($id),
        ]);
    }
}
