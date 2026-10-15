<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Usuarios</h3>
    <a href="/usuarios/nuevo" class="btn btn-primary">+ Nuevo usuario</a>
</div>

<table class="table table-striped bg-white">
    <thead><tr><th>Usuario</th><th>Nombre</th><th>RUT</th><th>Rol</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= esc($u['nom_usuario']) ?></td>
            <td><?= esc($u['nombres'] . ' ' . $u['apellidos']) ?></td>
            <td><?= esc($u['rut']) ?></td>
            <td><?= esc($u['nivel_acceso_nombre']) ?></td>
            <td><?= $u['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' ?></td>
            <td class="text-end">
                <a href="/usuarios/editar/<?= $u['id_usuario'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                <form action="/usuarios/eliminar/<?= $u['id_usuario'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Dar de baja a este usuario?');">
                    <button class="btn btn-sm btn-outline-danger">Dar de baja</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
