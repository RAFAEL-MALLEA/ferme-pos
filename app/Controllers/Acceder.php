<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Acceder extends BaseController
{
    public function index()
    {
        if ($this->session->get('logueado')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function autenticar()
    {
        $rules = [
            'nom_usuario' => 'required',
            'contrasena'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Ingresa usuario y contraseña.');
        }

        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->obtenerParaLogin($this->request->getPost('nom_usuario'));

        if (! $usuario || ! password_verify($this->request->getPost('contrasena'), $usuario['contrasena'])) {
            return redirect()->back()->withInput()->with('error', 'Usuario o contraseña incorrectos.');
        }

        $this->session->set([
            'logueado'             => true,
            'id_usuario'           => $usuario['id_usuario'],
            'nom_usuario'          => $usuario['nom_usuario'],
            'nombres'              => $usuario['nombres'],
            'nivel_acceso_fk'      => $usuario['nivel_acceso_fk'],
            'nivel_acceso_nombre'  => $usuario['nivel_acceso_nombre'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function salir()
    {
        $this->session->destroy();

        return redirect()->to('/login');
    }
}
