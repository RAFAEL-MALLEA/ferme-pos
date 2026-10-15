<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\VentaModel;

class Home extends BaseController
{
    public function index()
    {
        if ($this->session->get('logueado')) {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $productoModel = new ProductoModel();
        $ventaModel    = new VentaModel();

        $data = [
            'totalProductos'    => count($productoModel->catalogoActivo()),
            'productosStockBajo' => $productoModel->conStockBajo(),
            'ventasRecientes'   => array_slice($ventaModel->listarConDetalle(), 0, 5),
        ];

        return view('dashboard/index', $data);
    }
}
