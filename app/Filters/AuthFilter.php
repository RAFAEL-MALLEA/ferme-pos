<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Reemplaza el patron original donde cada controlador revisaba la sesion
 * "a mano" (y a veces se les olvidaba -> esa era una de las causas de los
 * "Undefined property $session" en los logs). Con un filtro centralizado,
 * ningun controlador nuevo puede "olvidarse" de pedir login.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('logueado')) {
            $session->setFlashdata('error', 'Debes iniciar sesion para continuar.');
            return redirect()->to('/login');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere accion posterior.
    }
}
