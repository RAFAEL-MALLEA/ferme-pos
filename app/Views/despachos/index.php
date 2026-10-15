<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Despachos</h3>

<table class="table table-striped bg-white">
    <thead><tr><th>#</th><th>Venta</th><th>Dirección</th><th>Transportista</th><th>Estado</th><th class="text-end">Acción</th></tr></thead>
    <tbody>
    <?php foreach ($despachos as $d): ?>
        <tr>
            <td><?= (int) $d['id_despacho'] ?></td>
            <td><a href="/ventas/ver/<?= $d['id_venta'] ?>">#<?= (int) $d['id_venta'] ?></a></td>
            <td><?= esc($d['direccion_entrega']) ?></td>
            <td><?= esc($d['transportista']) ?></td>
            <td><?= esc($d['estado']) ?></td>
            <td class="text-end">
                <form action="/despachos/actualizar-estado/<?= $d['id_despacho'] ?>" method="post" class="d-flex gap-1 justify-content-end">
                    <select name="estado" class="form-select form-select-sm" style="width:auto;">
                        <option value="pendiente" <?= $d['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="en_ruta" <?= $d['estado'] === 'en_ruta' ? 'selected' : '' ?>>En ruta</option>
                        <option value="entregado" <?= $d['estado'] === 'entregado' ? 'selected' : '' ?>>Entregado</option>
                    </select>
                    <button class="btn btn-sm btn-outline-primary">Actualizar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (empty($despachos)): ?>
        <tr><td colspan="6" class="text-center text-muted">No hay despachos registrados todavía.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
