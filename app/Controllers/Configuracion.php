<?php

namespace App\Controllers;

/**
 * BUG ORIGINAL: "Undefined variable $configuracion" (19x) -> la vista
 * esperaba datos que el controlador nunca armaba. Aqui se guarda la
 * configuracion en un archivo de datos simple dentro de writable/
 * (no se justificaba una tabla completa para 2-3 parametros del negocio).
 */
class Configuracion extends BaseController
{
    private string $archivo = WRITEPATH . 'config_negocio.json';

    public function index()
    {
        return view('dashboard/configuracion', [
            'configuracion' => $this->leer(),
        ]);
    }

    public function guardar()
    {
        $datos = [
            'nombre_empresa' => $this->request->getPost('nombre_empresa'),
            'direccion'      => $this->request->getPost('direccion'),
            'telefono'       => $this->request->getPost('telefono'),
        ];

        file_put_contents($this->archivo, json_encode($datos));

        return redirect()->to('/configuracion')->with('mensaje', 'Configuración guardada.');
    }

    private function leer(): array
    {
        if (! file_exists($this->archivo)) {
            return [
                'nombre_empresa' => 'Ferretería Ferme',
                'direccion'      => '',
                'telefono'       => '',
            ];
        }

        return json_decode(file_get_contents($this->archivo), true) ?? [];
    }
}
