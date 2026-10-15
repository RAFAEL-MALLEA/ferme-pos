<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController
 *
 * BUG ORIGINAL (visto en logs):
 *   "Undefined property: App\Controllers\Ventas::$input"
 *   "Undefined property: App\Controllers\Productos::$input"
 *   "Undefined property: CodeIgniter\View\View::$session"
 *
 * Eso pasa cuando un controlador NO llama a parent::initController(), que es
 * lo que deja disponibles $this->request y $this->session. Aqui se centraliza
 * y ademas se agregan helpers de sesion/rol que todos los controladores
 * del sistema reutilizan, para no repetir (y desincronizar) esa logica.
 */
abstract class BaseController extends Controller
{
    protected $helpers = ['form', 'url', 'rut'];

    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = service('session');
    }

    protected function usuarioId(): ?int
    {
        return $this->session->get('id_usuario');
    }

    protected function rolActual(): string
    {
        return strtolower((string) $this->session->get('nivel_acceso_nombre'));
    }

    protected function esAdministrador(): bool
    {
        return $this->rolActual() === 'administrador';
    }

    /**
     * Respuesta JSON estandar para los endpoints usados por AJAX
     * (ej. buscarPorRutJson, listarJson), para no repetir formato en cada uno.
     */
    protected function respuestaJson(bool $ok, $data = null, string $mensaje = '')
    {
        return $this->response->setJSON([
            'ok'      => $ok,
            'mensaje' => $mensaje,
            'data'    => $data,
        ]);
    }
}
