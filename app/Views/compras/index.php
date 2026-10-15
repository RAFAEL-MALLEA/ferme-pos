<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Órdenes de compra</h3>
    <a href="/compras/nueva" class="btn btn-primary">+ Nueva orden</a>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>#</th><th>Proveedor</th><th>Fecha</th><th>Total</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($ordenes as $o): ?>
        <tr>
            <td><?= (int) $o['id_orden_compra'] ?></td>
            <td><?= esc($o['razon_social']) ?></td>
            <td><?= esc($o['fecha']) ?></td>
            <td>$<?= number_format((float) $o['total'], 0, ',', '.') ?></td>
            <td>
                <?php $badge = ['pendiente' => 'warning', 'recibida' => 'success', 'anulada' => 'secondary'][$o['estado']] ?? 'secondary'; ?>
                <span class="badge bg-<?= $badge ?>"><?= esc($o['estado']) ?></span>
            </td>
            <td class="text-end">
                <a href="/compras/ver/<?= $o['id_orden_compra'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                <?php if ($o['estado'] === 'pendiente'): ?>
                <form action="/compras/recibir/<?= $o['id_orden_compra'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Confirmar recepción de mercadería? Esto sumará el stock.');">
                    <button class="btn btn-sm btn-outline-success">Recibir</button>
                </form>
                <form action="/compras/eliminar/<?= $o['id_orden_compra'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Anular orden?');">
                    <button class="btn btn-sm btn-outline-danger">Anular</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
