<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Nueva orden de compra</h3>

<form action="/compras/guardar" method="post" class="card card-body" style="max-width:700px;">
    <div class="mb-3">
        <label class="form-label">Proveedor</label>
        <select name="proveedor_fk" class="form-select" required>
            <option value="">Selecciona...</option>
            <?php foreach ($proveedores as $p): ?>
                <option value="<?= $p['id_proveedor'] ?>"><?= esc($p['razon_social']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <label class="form-label">Productos a pedir</label>
    <div id="lineas">
        <div class="row g-2 mb-2 linea">
            <div class="col-7">
                <select name="producto[]" class="form-select">
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id_producto'] ?>"><?= esc($p['nombre']) ?> ($<?= number_format((float) $p['precio_compra'], 0, ',', '.') ?> c/u)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <input type="number" name="cantidad[]" class="form-control" value="1" min="1">
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary mb-3" id="btnAgregarLinea">+ Agregar producto</button>

    <button type="submit" class="btn btn-primary">Crear orden</button>
    <a href="/compras" class="btn btn-link">Cancelar</a>
</form>

<script>
document.getElementById('btnAgregarLinea').addEventListener('click', () => {
    const base = document.querySelector('.linea');
    const clone = base.cloneNode(true);
    document.getElementById('lineas').appendChild(clone);
});
</script>

<?= $this->endSection() ?>
