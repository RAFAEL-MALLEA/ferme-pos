<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * NOTA IMPORTANTE SOBRE EL BUG ORIGINAL:
 * En los logs del proyecto perdido, el error mas frecuente (861 veces) era:
 *   "Too few arguments to function App\Controllers\Empresas::boolClienteEmpresa(),
 *    0 passed ... and exactly 1 expected"
 * Eso pasa cuando una ruta apunta a un metodo que EXIGE un parametro
 * obligatorio, pero la URL no trae ese segmento (ej. un link <a href="/empresas/bool">
 * sin el id). Aqui se corrige de dos formas a la vez:
 *   1) Cada ruta que necesita un parametro lo declara explicitamente ({id}, etc.)
 *   2) Los controladores (ver app/Controllers) nunca exigen un parametro
 *      obligatorio sin valor por defecto, como segunda linea de defensa.
 */

$routes->get('/', 'Home::index');

// ----- Autenticacion -----
$routes->get('login', 'Acceder::index');
$routes->post('login', 'Acceder::autenticar');
$routes->get('logout', 'Acceder::salir');

// ----- Area autenticada -----
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {

    $routes->get('dashboard', 'Home::dashboard');

    // Usuarios y accesos (solo administrador)
    $routes->group('usuarios', ['filter' => 'role:administrador'], static function (RouteCollection $routes) {
        $routes->get('/', 'Usuarios::index');
        $routes->get('nuevo', 'Usuarios::nuevo');
        $routes->post('guardar', 'Usuarios::guardar');
        $routes->get('editar/(:num)', 'Usuarios::editar/$1');
        $routes->post('actualizar/(:num)', 'Usuarios::actualizar/$1');
        $routes->post('eliminar/(:num)', 'Usuarios::eliminar/$1');
        $routes->get('buscar-rut/(:segment)', 'Usuarios::buscarPorRutJson/$1');
    });

    // Categorias
    $routes->group('categorias', ['filter' => 'role:administrador,bodeguero'], static function (RouteCollection $routes) {
        $routes->get('/', 'Categorias::index');
        $routes->post('guardar', 'Categorias::guardar');
        $routes->post('actualizar/(:num)', 'Categorias::actualizar/$1');
        $routes->post('eliminar/(:num)', 'Categorias::eliminar/$1');
    });

    // Productos (catalogo visible para todos los roles autenticados)
    $routes->get('productos', 'Productos::index');
    $routes->get('productos/json', 'Productos::listarJson');

    // Administracion de productos (admin y bodeguero)
    $routes->group('productos-admin', ['filter' => 'role:administrador,bodeguero'], static function (RouteCollection $routes) {
        $routes->get('/', 'ProductosAdmin::index');
        $routes->get('nuevo', 'ProductosAdmin::nuevo');
        $routes->post('guardar', 'ProductosAdmin::guardar');
        $routes->get('editar/(:num)', 'ProductosAdmin::editar/$1');
        $routes->post('actualizar/(:num)', 'ProductosAdmin::actualizar/$1');
        $routes->post('eliminar/(:num)', 'ProductosAdmin::eliminar/$1');
    });

    // Proveedores
    $routes->group('proveedores', ['filter' => 'role:administrador,bodeguero'], static function (RouteCollection $routes) {
        $routes->get('/', 'Proveedor::index');
        $routes->post('guardar', 'Proveedor::guardar');
        $routes->post('actualizar/(:num)', 'Proveedor::actualizar/$1');
        $routes->post('eliminar/(:num)', 'Proveedor::eliminar/$1');
    });

    // Ordenes de compra
    $routes->group('compras', ['filter' => 'role:administrador,bodeguero'], static function (RouteCollection $routes) {
        $routes->get('/', 'OrdenesCompra::index');
        $routes->get('nueva', 'OrdenesCompra::nueva');
        $routes->post('guardar', 'OrdenesCompra::guardar');
        $routes->get('ver/(:num)', 'OrdenesCompra::ver/$1');
        $routes->post('recibir/(:num)', 'OrdenesCompra::recibir/$1');
        $routes->post('eliminar/(:num)', 'OrdenesCompra::eliminar/$1');
    });

    // Empresas (clientes empresa)
    $routes->group('empresas', ['filter' => 'role:administrador,vendedor'], static function (RouteCollection $routes) {
        $routes->get('/', 'Empresas::index');
        $routes->post('guardar', 'Empresas::guardar');
        $routes->post('actualizar/(:num)', 'Empresas::actualizar/$1');
        $routes->post('eliminar/(:num)', 'Empresas::eliminar/$1');
        $routes->get('es-cliente/(:num)', 'Empresas::boolClienteEmpresa/$1');
    });

    // Punto de venta / carrito
    $routes->group('ventas', ['filter' => 'role:administrador,vendedor'], static function (RouteCollection $routes) {
        $routes->get('/', 'Ventas::index');
        $routes->post('carrito/agregar', 'Ventas::agregarCarrito');
        $routes->post('carrito/quitar/(:num)', 'Ventas::quitarCarrito/$1');
        $routes->post('carrito/vaciar', 'Ventas::vaciarCarrito');
        $routes->post('finalizar', 'Ventas::finalizar');
        $routes->get('historial', 'Ventas::historial');
        $routes->get('ver/(:num)', 'Ventas::ver/$1');
    });

    // Notas de credito
    $routes->group('notas-credito', ['filter' => 'role:administrador,vendedor'], static function (RouteCollection $routes) {
        $routes->get('/', 'NotasCredito::index');
        $routes->get('nueva/(:num)', 'NotasCredito::nueva/$1');
        $routes->post('guardar/(:num)', 'NotasCredito::guardar/$1');
    });

    // Despachos
    $routes->group('despachos', ['filter' => 'role:administrador,bodeguero'], static function (RouteCollection $routes) {
        $routes->get('/', 'Despachos::index');
        $routes->post('actualizar-estado/(:num)', 'Despachos::actualizarEstado/$1');
    });

    // Reportes / exportaciones
    $routes->get('reportes/venta-pdf/(:num)', 'Reportes::ventaPdf/$1');
    $routes->get('reportes/productos-excel', 'Xlsx::productosExcel');
    $routes->get('reportes/ventas-excel', 'Xlsx::ventasExcel');

    // Estadisticas / configuracion (solo admin)
    $routes->group('', ['filter' => 'role:administrador'], static function (RouteCollection $routes) {
        $routes->get('estadisticas', 'Estadistica::index');
        $routes->get('configuracion', 'Configuracion::index');
        $routes->post('configuracion/guardar', 'Configuracion::guardar');
        $routes->get('monedas', 'Moneda::index');
        $routes->post('monedas/guardar', 'Moneda::guardar');
        $routes->post('monedas/actualizar/(:num)', 'Moneda::actualizar/$1');
    });
});
