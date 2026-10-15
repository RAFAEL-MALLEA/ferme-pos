<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtro de autorizacion por rol. Se usa como 'role:administrador,vendedor'
 * en las rutas. Corrige el problema original donde la logica de permisos
 * estaba (quizas) repetida/duplicada dentro de cada metodo de cada
 * controlador, con alto riesgo de que a alguno se le olvidara validar.
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session   = session();
        $rolActual = strtolower((string) $session->get('nivel_acceso_nombre'));

        if (empty($arguments)) {
            return null; // Sin roles especificos exigidos, basta con estar logueado.
        }

        $rolesPermitidos = array_map('strtolower', $arguments);

        if (! in_array($rolActual, $rolesPermitidos, true)) {
            return redirect()->to('/dashboard')
                ->with('error', 'No tienes permisos para acceder a esa seccion.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere accion posterior.
    }
}
