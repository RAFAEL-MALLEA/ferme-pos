<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo ?? 'Ferme POS') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php if (session()->get('logueado')): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard">🔧 Ferme POS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ventas">Punto de venta</a></li>
                <li class="nav-item"><a class="nav-link" href="/ventas/historial">Historial ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="/productos-admin">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="/categorias">Categorías</a></li>
                <li class="nav-item"><a class="nav-link" href="/proveedores">Proveedores</a></li>
                <li class="nav-item"><a class="nav-link" href="/compras">Compras</a></li>
                <li class="nav-item"><a class="nav-link" href="/empresas">Empresas</a></li>
                <li class="nav-item"><a class="nav-link" href="/notas-credito">Notas crédito</a></li>
                <li class="nav-item"><a class="nav-link" href="/despachos">Despachos</a></li>
                <?php if (strtolower((string) session()->get('nivel_acceso_nombre')) === 'administrador'): ?>
                <li class="nav-item"><a class="nav-link" href="/usuarios">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="/estadisticas">Estadísticas</a></li>
                <li class="nav-item"><a class="nav-link" href="/configuracion">Configuración</a></li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text text-white me-3">
                <?= esc(session()->get('nombres')) ?> · <?= esc(session()->get('nivel_acceso_nombre')) ?>
            </span>
            <a href="/logout" class="btn btn-outline-light btn-sm">Salir</a>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container-fluid py-4">
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('mensaje')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('contenido') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
