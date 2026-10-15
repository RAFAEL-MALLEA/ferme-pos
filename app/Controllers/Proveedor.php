<?php

namespace App\Controllers;

use App\Models\ProveedorModel;

class Proveedor extends BaseController
{
    public function index()
    {
        $model = new ProveedorModel();

        return view('proveedores/index', [
            'proveedores' => $model->where('estado', 1)->orderBy('razon_social', 'ASC')->findAll(),
        ]);
    }

    public function guardar()
    {
        $rules = [
            'razon_social' => 'required|max_length[120]',
            'rut'          => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rut = $this->request->getPost('rut');
        if (! validar_rut($rut)) {
            return redirect()->back()->withInput()->with('error', 'RUT de proveedor inválido.');
        }

        (new ProveedorModel())->insert([
            'razon_social' => $this->request->getPost('razon_social'),
            'rut'          => formatear_rut($rut),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
            'direccion'    => $this->request->getPost('direccion'),
            'estado'       => 1,
        ]);

        return redirect()->to('/proveedores')->with('mensaje', 'Proveedor creado.');
    }

    public function actualizar(int $id)
    {
        (new ProveedorModel())->update($id, [
            'razon_social' => $this->request->getPost('razon_social'),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
            'direccion'    => $this->request->getPost('direccion'),
        ]);

        return redirect()->to('/proveedores')->with('mensaje', 'Proveedor actualizado.');
    }

    public function eliminar(int $id)
    {
        (new ProveedorModel())->darDeBaja($id);

        return redirect()->to('/proveedores')->with('mensaje', 'Proveedor eliminado.');
    }
}
