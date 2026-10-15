<?php

namespace App\Controllers;

use App\Models\DespachoModel;

class Despachos extends BaseController
{
    public function index()
    {
        return view('despachos/index', [
            'despachos' => (new DespachoModel())->listarConVenta(),
        ]);
    }

    public function actualizarEstado(int $id)
    {
        $estado = $this->request->getPost('estado');

        if (! in_array($estado, ['pendiente', 'en_ruta', 'entregado'], true)) {
            return redirect()->back()->with('error', 'Estado inválido.');
        }

        (new DespachoModel())->update($id, ['estado' => $estado]);

        return redirect()->to('/despachos')->with('mensaje', 'Estado del despacho actualizado.');
    }
}
