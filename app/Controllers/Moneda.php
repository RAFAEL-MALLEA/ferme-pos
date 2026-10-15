<?php

namespace App\Controllers;

use App\Models\MonedaModel;

class Moneda extends BaseController
{
    public function index()
    {
        return view('dashboard/monedas', [
            'monedas' => (new MonedaModel())->findAll(),
        ]);
    }

    public function guardar()
    {
        $rules = [
            'nombre'  => 'required',
            'simbolo' => 'required|max_length[5]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new MonedaModel())->insert([
            'nombre'      => $this->request->getPost('nombre'),
            'simbolo'     => $this->request->getPost('simbolo'),
            'tasa_cambio' => $this->request->getPost('tasa_cambio') ?: 1,
            'por_defecto' => 0,
        ]);

        return redirect()->to('/monedas')->with('mensaje', 'Moneda agregada.');
    }

    public function actualizar(int $id)
    {
        (new MonedaModel())->update($id, [
            'tasa_cambio' => $this->request->getPost('tasa_cambio'),
        ]);

        return redirect()->to('/monedas')->with('mensaje', 'Tasa de cambio actualizada.');
    }
}
