<?php

namespace App\Controllers;

use App\Models\EmpresaModel;

/**
 * BUG ORIGINAL: boolClienteEmpresa() era el error #1 de todo el sistema
 * (861 ocurrencias) porque se llamaba sin el id obligatorio. Aqui la ruta
 * "empresas/es-cliente/(:num)" SIEMPRE entrega el id, y el metodo delega
 * la consulta real al modelo (ver EmpresaModel::esClienteEmpresa).
 */
class Empresas extends BaseController
{
    public function index()
    {
        $model = new EmpresaModel();

        return view('empresas/index', [
            'empresas' => $model->where('estado', 1)->orderBy('razon_social', 'ASC')->findAll(),
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
            return redirect()->back()->withInput()->with('error', 'RUT de empresa inválido.');
        }

        (new EmpresaModel())->insert([
            'razon_social' => $this->request->getPost('razon_social'),
            'rut'          => formatear_rut($rut),
            'giro'         => $this->request->getPost('giro'),
            'direccion'    => $this->request->getPost('direccion'),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
            'estado'       => 1,
        ]);

        return redirect()->to('/empresas')->with('mensaje', 'Empresa creada.');
    }

    public function actualizar(int $id)
    {
        (new EmpresaModel())->update($id, [
            'razon_social' => $this->request->getPost('razon_social'),
            'giro'         => $this->request->getPost('giro'),
            'direccion'    => $this->request->getPost('direccion'),
            'telefono'     => $this->request->getPost('telefono'),
            'email'        => $this->request->getPost('email'),
        ]);

        return redirect()->to('/empresas')->with('mensaje', 'Empresa actualizada.');
    }

    public function eliminar(int $id)
    {
        (new EmpresaModel())->darDeBaja($id);

        return redirect()->to('/empresas')->with('mensaje', 'Empresa eliminada.');
    }

    public function boolClienteEmpresa(int $id)
    {
        $esCliente = (new EmpresaModel())->esClienteEmpresa($id);

        return $this->respuestaJson(true, ['es_cliente' => $esCliente]);
    }
}
