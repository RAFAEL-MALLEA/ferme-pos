<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Empresas (clientes con factura)</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNueva">+ Nueva empresa</button>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>Razón social</th><th>RUT</th><th>Giro</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($empresas as $e): ?>
        <tr>
            <td><?= esc($e['razon_social']) ?></td>
            <td><?= esc($e['rut']) ?></td>
            <td><?= esc($e['giro']) ?></td>
            <td class="text-end">
                <form action="/empresas/eliminar/<?= $e['id_empresa'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar empresa?');">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="modalNueva">
  <div class="modal-dialog">
    <form action="/empresas/guardar" method="post" class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Nueva empresa</h5></div>
        <div class="modal-body">
            <div class="mb-2"><label class="form-label">Razón social</label><input type="text" name="razon_social" class="form-control" required></div>
            <div class="mb-2"><label class="form-label">RUT</label><input type="text" name="rut" class="form-control" placeholder="76123456-7" required></div>
            <div class="mb-2"><label class="form-label">Giro</label><input type="text" name="giro" class="form-control"></div>
            <div class="mb-2"><label class="form-label">Dirección</label><input type="text" name="direccion" class="form-control"></div>
            <div class="mb-2"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control"></div>
            <div class="mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary">Crear</button></div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
