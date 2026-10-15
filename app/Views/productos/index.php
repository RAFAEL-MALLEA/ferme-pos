<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Productos</h3>
    <div>
        <a href="/reportes/productos-excel" class="btn btn-outline-success">Exportar Excel</a>
        <a href="/productos-admin/nuevo" class="btn btn-primary">+ Nuevo producto</a>
    </div>
</div>

<table class="table table-striped bg-white align-middle">
    <thead><tr><th>Nombre</th><th>Categoría</th><th>Precio venta</th><th>Stock</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= esc($p['nombre']) ?></td>
            <td><?= esc($p['categoria_nombre']) ?></td>
            <td>$<?= number_format((float) $p['precio_venta'], 0, ',', '.') ?></td>
            <td><?= (int) $p['stock'] ?><?= $p['stock'] <= $p['stock_minimo'] ? ' ⚠️' : '' ?></td>
            <td><?= $p['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' ?></td>
            <td class="text-end">
                <a href="/productos-admin/editar/<?= $p['id_producto'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                <form action="/productos-admin/eliminar/<?= $p['id_producto'] ?>" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar producto?');">
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
