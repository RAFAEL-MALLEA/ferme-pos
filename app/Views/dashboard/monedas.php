<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Monedas</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNueva">+ Nueva moneda</button>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>Nombre</th><th>Símbolo</th><th>Tasa de cambio</th><th>Por defecto</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($monedas as $m): ?>
        <tr>
            <td><?= esc($m['nombre']) ?></td>
            <td><?= esc($m['simbolo']) ?></td>
            <td>
                <form action="/monedas/actualizar/<?= $m['id_moneda'] ?>" method="post" class="d-flex gap-1">
                    <input type="number" step="0.0001" name="tasa_cambio" value="<?= esc($m['tasa_cambio']) ?>" class="form-control form-control-sm" style="width:120px;">
                    <button class="btn btn-sm btn-outline-primary">Actualizar</button>
                </form>
            </td>
            <td><?= $m['por_defecto'] ? '✅' : '' ?></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="modalNueva">
  <div class="modal-dialog">
    <form action="/monedas/guardar" method="post" class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Nueva moneda</h5></div>
        <div class="modal-body">
            <div class="mb-2"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" required></div>
            <div class="mb-2"><label class="form-label">Símbolo</label><input type="text" name="simbolo" class="form-control" required></div>
            <div class="mb-2"><label class="form-label">Tasa de cambio</label><input type="number" step="0.0001" name="tasa_cambio" class="form-control" value="1"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary">Crear</button></div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
