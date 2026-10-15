<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Proveedores</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">+ Nuevo proveedor</button>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>Razón social</th><th>RUT</th><th>Teléfono</th><th>Email</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($proveedores as $p): ?>
        <tr>
            <td><?= esc($p['razon_social']) ?></td>
            <td><?= esc($p['rut']) ?></td>
            <td><?= esc($p['telefono']) ?></td>
            <td><?= esc($p['email']) ?></td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editar<?= $p['id_proveedor'] ?>">Editar</button>
                <form action="/proveedores/eliminar/<?= $p['id_proveedor'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar proveedor?');">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
            </td>
        </tr>

        <div class="modal fade" id="editar<?= $p['id_proveedor'] ?>">
          <div class="modal-dialog">
            <form action="/proveedores/actualizar/<?= $p['id_proveedor'] ?>" method="post" class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Editar proveedor</h5></div>
                <div class="modal-body">
                    <div class="mb-2"><label class="form-label">Razón social</label>
                        <input type="text" name="razon_social" class="form-control" value="<?= esc($p['razon_social']) ?>" required></div>
                    <div class="mb-2"><label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= esc($p['telefono']) ?>"></div>
                    <div class="mb-2"><label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($p['email']) ?>"></div>
                    <div class="mb-2"><label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="<?= esc($p['direccion']) ?>"></div>
                </div>
                <div class="modal-footer"><button class="btn btn-primary">Guardar</button></div>
            </form>
          </div>
        </div>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="modalNuevo">
  <div class="modal-dialog">
    <form action="/proveedores/guardar" method="post" class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Nuevo proveedor</h5></div>
        <div class="modal-body">
            <div class="mb-2"><label class="form-label">Razón social</label><input type="text" name="razon_social" class="form-control" required></div>
            <div class="mb-2"><label class="form-label">RUT</label><input type="text" name="rut" class="form-control" placeholder="12345678-9" required></div>
            <div class="mb-2"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control"></div>
            <div class="mb-2"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
            <div class="mb-2"><label class="form-label">Dirección</label><input type="text" name="direccion" class="form-control"></div>
        </div>
        <div class="modal-footer"><button class="btn btn-primary">Crear</button></div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
