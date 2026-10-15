<?php

namespace App\Controllers;

use App\Models\DetalleVentaModel;
use App\Models\NotaCreditoModel;
use App\Models\ProductoModel;
use App\Models\VentaModel;

class NotasCredito extends BaseController
{
    public function index()
    {
        $model = new NotaCreditoModel();

        return view('notas_credito/index', [
            'notas' => $model->select('nota_credito.*, usuario.nom_usuario')
                ->join('usuario', 'usuario.id_usuario = nota_credito.usuario_fk')
                ->orderBy('nota_credito.fecha', 'DESC')
                ->findAll(),
        ]);
    }

    public function nueva(int $idVenta)
    {
        $venta = (new VentaModel())->find($idVenta);

        if (! $venta) {
            return redirect()->to('/ventas/historial')->with('error', 'Venta no encontrada.');
        }

        return view('notas_credito/form', [
            'venta'    => $venta,
            'detalles' => (new DetalleVentaModel())->porVenta($idVenta),
        ]);
    }

    /**
     * Genera la nota de credito y REPONE el stock de los productos
     * devueltos. En el proyecto original no habia evidencia de este
     * flujo de reversa de stock.
     */
    public function guardar(int $idVenta)
    {
        $rules = [
            'motivo' => 'required|max_length[255]',
            'monto'  => 'required|decimal',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $venta = (new VentaModel())->find($idVenta);
        if (! $venta) {
            return redirect()->to('/ventas/historial')->with('error', 'Venta no encontrada.');
        }

        (new NotaCreditoModel())->insert([
            'venta_fk'   => $idVenta,
            'usuario_fk' => $this->usuarioId(),
            'motivo'     => $this->request->getPost('motivo'),
            'monto'      => $this->request->getPost('monto'),
            'fecha'      => date('Y-m-d H:i:s'),
        ]);

        $reponerStock = $this->request->getPost('reponer_stock');
        if ($reponerStock) {
            $productoModel = new ProductoModel();
            foreach ((new DetalleVentaModel())->porVenta($idVenta) as $detalle) {
                $productoModel->aumentarStock($detalle['id_producto_fk'], $detalle['cantidad']);
            }
        }

        return redirect()->to('/notas-credito')->with('mensaje', 'Nota de crédito generada.');
    }
}
