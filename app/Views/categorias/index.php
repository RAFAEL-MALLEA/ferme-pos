<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Categorías</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNueva">+ Nueva categoría</button>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>Nombre</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($categorias as $c): ?>
        <tr>
            <td><?= esc($c['nombre']) ?></td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editar<?= $c['id_categoria'] ?>">Editar</button>
                <form action="/categorias/eliminar/<?= $c['id_categoria'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
            </td>
        </tr>

        <div class="modal fade" id="editar<?= $c['id_categoria'] ?>">
          <div class="modal-dialog">
            <form action="/categorias/actualizar/<?= $c['id_categoria'] ?>" method="post" class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Editar categoría</h5></div>
                <div class="modal-body">
                    <input type="text" name="nombre" class="form-control" value="<?= esc($c['nombre']) ?>" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
          </div>
        </div>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="modalNueva">
  <div class="modal-dialog">
    <form action="/categorias/guardar" method="post" class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Nueva categoría</h5></div>
        <div class="modal-body">
            <input type="text" name="nombre" class="form-control" placeholder="Nombre de la categoría" required>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Crear</button>
        </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
