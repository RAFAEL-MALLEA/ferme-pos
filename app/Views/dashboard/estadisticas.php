<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Estadísticas</h3>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Ventas por mes</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($ventasPorMes as $v): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= esc($v['mes']) ?>
                        <strong>$<?= number_format((float) $v['total'], 0, ',', '.') ?></strong>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($ventasPorMes)): ?>
                    <li class="list-group-item text-muted">Aún no hay ventas registradas.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Productos más vendidos</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($masVendidos as $m): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= esc($m['nombre']) ?>
                        <strong><?= (int) $m['total_vendido'] ?> uds.</strong>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($masVendidos)): ?>
                    <li class="list-group-item text-muted">Aún no hay ventas registradas.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">⚠️ Stock bajo</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($productosStockBajo as $p): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= esc($p['nombre']) ?>
                        <span class="badge bg-danger">Stock: <?= (int) $p['stock'] ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($productosStockBajo)): ?>
                    <li class="list-group-item text-muted">Todo el stock está en buen nivel.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
