<?php

namespace App\Controllers;

use App\Models\DetalleVentaModel;
use App\Models\VentaModel;
use FPDF; // setasign/fpdf expone la clase FPDF en el namespace global, sin sub-namespace.

/**
 * BUG ORIGINAL en logs:
 *   - "FPDF error: The document is closed" (19x)
 *   - "FPDF error: Some data has already been output, can't send PDF file" (15x)
 * Esto pasa cuando algo (un espacio en blanco, un echo, un error PHP) se
 * imprime ANTES de $pdf->Output(). Aqui el controlador NO hace echo de
 * nada mas, no deja espacios fuera de las etiquetas PHP, y usa
 * ob_clean() como salvavidas justo antes de enviar el PDF.
 */
class Reportes extends BaseController
{
    public function ventaPdf(int $idVenta)
    {
        $venta = (new VentaModel())->find($idVenta);

        if (! $venta) {
            return redirect()->back()->with('error', 'Venta no encontrada.');
        }

        $detalles = (new DetalleVentaModel())->porVenta($idVenta);

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Ferreteria Ferme - Comprobante de Venta', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, 'Venta N° ' . $venta['id_venta'] . '   Fecha: ' . $venta['fecha'], 0, 1);
        $pdf->Ln(4);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(90, 8, 'Producto', 1);
        $pdf->Cell(30, 8, 'Cantidad', 1, 0, 'C');
        $pdf->Cell(35, 8, 'Precio Unit.', 1, 0, 'C');
        $pdf->Cell(35, 8, 'Subtotal', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        foreach ($detalles as $detalle) {
            $pdf->Cell(90, 8, $detalle['producto_nombre'], 1);
            $pdf->Cell(30, 8, (string) $detalle['cantidad'], 1, 0, 'C');
            $pdf->Cell(35, 8, number_format((float) $detalle['precio_unitario'], 0, ',', '.'), 1, 0, 'R');
            $pdf->Cell(35, 8, number_format((float) $detalle['subtotal'], 0, ',', '.'), 1, 1, 'R');
        }

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Total: $' . number_format((float) $venta['total'], 0, ',', '.'), 0, 1, 'R');

        if (ob_get_length()) {
            ob_clean();
        }

        $pdf->Output('I', 'venta_' . $idVenta . '.pdf');
        exit;
    }
}
