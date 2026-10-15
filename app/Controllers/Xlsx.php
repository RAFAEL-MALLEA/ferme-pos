<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\VentaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class Xlsx extends BaseController
{
    public function productosExcel()
    {
        $productos = (new ProductoModel())->select('producto.*, categoria.nombre as categoria_nombre')
            ->join('categoria', 'categoria.id_categoria = producto.categoria')
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Productos');

        $sheet->fromArray(['ID', 'Nombre', 'Categoría', 'Precio Venta', 'Stock', 'Estado'], null, 'A1');

        $fila = 2;
        foreach ($productos as $producto) {
            $sheet->fromArray([
                $producto['id_producto'],
                $producto['nombre'],
                $producto['categoria_nombre'],
                $producto['precio_venta'],
                $producto['stock'],
                $producto['estado'] ? 'Activo' : 'Inactivo',
            ], null, 'A' . $fila);
            $fila++;
        }

        return $this->descargarExcel($spreadsheet, 'productos.xlsx');
    }

    public function ventasExcel()
    {
        $ventas = (new VentaModel())->listarConDetalle();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ventas');

        $sheet->fromArray(['ID Venta', 'Fecha', 'Vendedor', 'Total', 'Estado'], null, 'A1');

        $fila = 2;
        foreach ($ventas as $venta) {
            $sheet->fromArray([
                $venta['id_venta'],
                $venta['fecha'],
                $venta['nom_usuario'],
                $venta['total'],
                $venta['estado'],
            ], null, 'A' . $fila);
            $fila++;
        }

        return $this->descargarExcel($spreadsheet, 'ventas.xlsx');
    }

    /**
     * Centraliza la salida del archivo para que, igual que con los PDF,
     * nunca haya output previo que corrompa el archivo descargado.
     */
    private function descargarExcel(Spreadsheet $spreadsheet, string $nombreArchivo)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        $writer = new XlsxWriter($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
