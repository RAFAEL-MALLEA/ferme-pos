<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4"><?= $producto ? 'Editar producto' : 'Nuevo producto' ?></h3>

<form action="<?= $producto ? '/productos-admin/actualizar/' . $producto['id_producto'] : '/productos-admin/guardar' ?>"
      method="post" enctype="multipart/form-data" class="card card-body" style="max-width:600px;">

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= esc($producto['nombre'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control"><?= esc($producto['descripcion'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Categoría</label>
        <select name="categoria" class="form-select" required>
            <option value="">Selecciona...</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id_categoria'] ?>" <?= (isset($producto) && $producto['categoria'] == $c['id_categoria']) ? 'selected' : '' ?>>
                    <?= esc($c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Proveedor</label>
        <select name="proveedor_fk" class="form-select">
            <option value="">Sin proveedor</option>
            <?php foreach ($proveedores as $p): ?>
                <option value="<?= $p['id_proveedor'] ?>" <?= (isset($producto) && $producto['proveedor_fk'] == $p['id_proveedor']) ? 'selected' : '' ?>>
                    <?= esc($p['razon_social']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Precio compra</label>
            <input type="number" step="0.01" name="precio_compra" class="form-control" value="<?= esc($producto['precio_compra'] ?? 0) ?>">
        </div>
        <div class="col mb-3">
            <label class="form-label">Precio venta</label>
            <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?= esc($producto['precio_venta'] ?? 0) ?>" required>
        </div>
    </div>

    <div class="row">
        <?php if (! $producto): ?>
        <div class="col mb-3">
            <label class="form-label">Stock inicial</label>
            <input type="number" name="stock" class="form-control" value="0">
        </div>
        <?php endif; ?>
        <div class="col mb-3">
            <label class="form-label">Stock mínimo</label>
            <input type="number" name="stock_minimo" class="form-control" value="<?= esc($producto['stock_minimo'] ?? 5) ?>">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Fecha de vencimiento (opcional)</label>
        <input type="date" name="fecha_vencimiento" class="form-control" value="<?= esc($producto['fecha_vencimiento'] ?? '') ?>">
        <div class="form-text">Déjalo vacío si el producto no vence (herramientas, tornillos, etc.).</div>
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="/productos-admin" class="btn btn-link">Cancelar</a>
</form>

<?= $this->endSection() ?>
