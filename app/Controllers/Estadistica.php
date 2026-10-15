<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\VentaModel;

class Estadistica extends BaseController
{
    public function index()
    {
        $ventaModel    = new VentaModel();
        $productoModel = new ProductoModel();

        $db = db_connect();

        $ventasPorMes = $db->table('venta')
            ->select("DATE_FORMAT(fecha, '%Y-%m') as mes, SUM(total) as total")
            ->where('estado', 'completada')
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->get()->getResultArray();

        $masVendidos = $db->table('detalle_venta')
            ->select('producto.nombre, SUM(detalle_venta.cantidad) as total_vendido')
            ->join('producto', 'producto.id_producto = detalle_venta.id_producto_fk')
            ->groupBy('producto.nombre')
            ->orderBy('total_vendido', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return view('dashboard/estadisticas', [
            'ventasPorMes'       => $ventasPorMes,
            'masVendidos'        => $masVendidos,
            'productosStockBajo' => $productoModel->conStockBajo(),
        ]);
    }
}
