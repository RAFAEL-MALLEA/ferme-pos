<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Dashboard</h3>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6>Productos activos</h6>
                <h2><?= (int) $totalProductos ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h6>Productos con stock bajo</h6>
                <h2><?= count($productosStockBajo) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <h6>Ventas registradas (recientes)</h6>
                <h2><?= count($ventasRecientes) ?></h2>
            </div>
        </div>
    </div>
</div>

<?php if (! empty($productosStockBajo)): ?>
<div class="card mb-4">
    <div class="card-header">⚠️ Productos con stock bajo</div>
    <ul class="list-group list-group-flush">
        <?php foreach ($productosStockBajo as $p): ?>
            <li class="list-group-item d-flex justify-content-between">
                <?= esc($p['nombre']) ?>
                <span class="badge bg-danger">Stock: <?= (int) $p['stock'] ?> (mínimo <?= (int) $p['stock_minimo'] ?>)</span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">Últimas ventas</div>
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Fecha</th><th>Vendedor</th><th>Total</th></tr></thead>
        <tbody>
        <?php foreach ($ventasRecientes as $v): ?>
            <tr>
                <td><?= (int) $v['id_venta'] ?></td>
                <td><?= esc($v['fecha']) ?></td>
                <td><?= esc($v['nom_usuario']) ?></td>
                <td>$<?= number_format((float) $v['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
