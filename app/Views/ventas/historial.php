<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Historial de ventas</h3>
    <a href="/reportes/ventas-excel" class="btn btn-outline-success">Exportar Excel</a>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>#</th><th>Fecha</th><th>Vendedor</th><th>Total</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($ventas as $v): ?>
        <tr>
            <td><?= (int) $v['id_venta'] ?></td>
            <td><?= esc($v['fecha']) ?></td>
            <td><?= esc($v['nom_usuario']) ?></td>
            <td>$<?= number_format((float) $v['total'], 0, ',', '.') ?></td>
            <td><?= esc($v['estado']) ?></td>
            <td class="text-end">
                <a href="/ventas/ver/<?= $v['id_venta'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                <a href="/reportes/venta-pdf/<?= $v['id_venta'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">PDF</a>
                <a href="/notas-credito/nueva/<?= $v['id_venta'] ?>" class="btn btn-sm btn-outline-warning">Nota crédito</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
