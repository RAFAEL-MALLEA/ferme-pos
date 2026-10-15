<?php

namespace App\Controllers;

use App\Models\DatosPersonalesModel;
use App\Models\NivelAccesoModel;
use App\Models\UsuarioModel;

/**
 * BUG ORIGINAL en logs:
 *   - "Too few arguments ... buscarPorRutJson(), 0 passed ... exactly 1 expected" (54x)
 *   - "Too few arguments ... editarUsuario(), 1 passed ... at least 2 expected"
 *   - "Call to undefined method App\Controllers\Usuarios::listar()"
 * Aqui cada metodo que necesita un parametro lo recibe SIEMPRE desde la ruta
 * (ver Routes.php), y se agrega el metodo index() que faltaba.
 */
class Usuarios extends BaseController
{
    public function index()
    {
        $usuarioModel = new UsuarioModel();

        return view('usuarios/index', [
            'usuarios' => $usuarioModel->listarConDetalle(),
        ]);
    }

    public function nuevo()
    {
        $nivelAccesoModel = new NivelAccesoModel();
        $comunaModel      = new \App\Models\ComunaModel();

        return view('usuarios/form', [
            'niveles' => $nivelAccesoModel->findAll(),
            'comunas' => $comunaModel->listarConRegion(),
            'usuario' => null,
        ]);
    }

    public function guardar()
    {
        $rules = [
            'rut'             => 'required',
            'nombres'         => 'required',
            'apellidos'       => 'required',
            'comuna_fk'       => 'required|is_natural_no_zero',
            'nom_usuario'     => 'required|min_length[4]|is_unique[usuario.nom_usuario]',
            'contrasena'      => 'required|min_length[6]',
            'nivel_acceso_fk' => 'required|is_natural_no_zero',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $rut = $this->request->getPost('rut');

        if (! validar_rut($rut)) {
            return redirect()->back()->withInput()->with('error', 'El RUT ingresado no es válido.');
        }

        $rut = formatear_rut($rut);

        $datosPersonalesModel = new DatosPersonalesModel();
        $datosPersonalesModel->guardarFicha([
            'rut'              => $rut,
            'nombres'          => $this->request->getPost('nombres'),
            'apellidos'        => $this->request->getPost('apellidos'),
            'natural_juridico' => 'N',
            'direccion'        => $this->request->getPost('direccion'),
            'telefono'         => $this->request->getPost('telefono'),
            'email'            => $this->request->getPost('email'),
            'comuna_fk'        => $this->request->getPost('comuna_fk'),
        ]);

        $usuarioModel = new UsuarioModel();
        $usuarioModel->insert([
            'rut_fk'          => $rut,
            'nom_usuario'     => $this->request->getPost('nom_usuario'),
            'contrasena'      => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT),
            'nivel_acceso_fk' => $this->request->getPost('nivel_acceso_fk'),
            'estado'          => 1,
        ]);

        return redirect()->to('/usuarios')->with('mensaje', 'Usuario creado correctamente.');
    }

    public function editar(int $id)
    {
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->find($id);

        if (! $usuario) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $datosPersonalesModel = new DatosPersonalesModel();
        $nivelAccesoModel     = new NivelAccesoModel();
        $comunaModel          = new \App\Models\ComunaModel();

        return view('usuarios/form', [
            'usuario'      => $usuario,
            'datosPersona' => $datosPersonalesModel->obtenerPorRut($usuario['rut_fk']),
            'niveles'      => $nivelAccesoModel->findAll(),
            'comunas'      => $comunaModel->listarConRegion(),
        ]);
    }

    public function actualizar(int $id)
    {
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->find($id);

        if (! $usuario) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $datosPersonalesModel = new DatosPersonalesModel();
        $datosPersonalesModel->guardarFicha([
            'rut'              => $usuario['rut_fk'],
            'nombres'          => $this->request->getPost('nombres'),
            'apellidos'        => $this->request->getPost('apellidos'),
            'natural_juridico' => 'N',
            'direccion'        => $this->request->getPost('direccion'),
            'telefono'         => $this->request->getPost('telefono'),
            'email'            => $this->request->getPost('email'),
            'comuna_fk'        => $this->request->getPost('comuna_fk'),
        ]);

        $datosActualizar = [
            'nivel_acceso_fk' => $this->request->getPost('nivel_acceso_fk'),
        ];

        $nuevaClave = $this->request->getPost('contrasena');
        if (! empty($nuevaClave)) {
            $datosActualizar['contrasena'] = password_hash($nuevaClave, PASSWORD_DEFAULT);
        }

        $usuarioModel->update($id, $datosActualizar);

        return redirect()->to('/usuarios')->with('mensaje', 'Usuario actualizado correctamente.');
    }

    /**
     * Baja logica (estado = 0), NUNCA delete() fisico: el usuario original
     * rompia por la FK fk_rut cuando el usuario ya tenia ventas asociadas.
     */
    public function eliminar(int $id)
    {
        $usuarioModel = new UsuarioModel();
        $usuarioModel->darDeBaja($id);

        return redirect()->to('/usuarios')->with('mensaje', 'Usuario dado de baja.');
    }

    public function buscarPorRutJson(string $rut)
    {
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->buscarPorRut($rut);

        if (! $usuario) {
            return $this->respuestaJson(false, null, 'No se encontró un usuario con ese RUT.');
        }

        return $this->respuestaJson(true, $usuario);
    }
}
