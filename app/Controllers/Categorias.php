<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class Categorias extends BaseController
{
    public function index()
    {
        $model = new CategoriaModel();

        return view('categorias/index', [
            'categorias' => $model->where('estado', 1)->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function guardar()
    {
        $rules = ['nombre' => 'required|min_length[3]|max_length[80]'];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        (new CategoriaModel())->insert([
            'nombre' => $this->request->getPost('nombre'),
            'estado' => 1,
        ]);

        return redirect()->to('/categorias')->with('mensaje', 'Categoría creada.');
    }

    public function actualizar(int $id)
    {
        (new CategoriaModel())->update($id, [
            'nombre' => $this->request->getPost('nombre'),
        ]);

        return redirect()->to('/categorias')->with('mensaje', 'Categoría actualizada.');
    }

    public function eliminar(int $id)
    {
        (new CategoriaModel())->darDeBaja($id);

        return redirect()->to('/categorias')->with('mensaje', 'Categoría eliminada.');
    }
}
