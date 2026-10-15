<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<div class="row">
    <div class="col-md-7">
        <h4>Catálogo</h4>
        <input type="text" id="buscador" class="form-control mb-3" placeholder="Buscar producto...">
        <div class="row g-2" id="listaProductos">
            <?php foreach ($productos as $p): ?>
                <div class="col-md-4 item-producto" data-nombre="<?= esc(strtolower($p['nombre'])) ?>">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6><?= esc($p['nombre']) ?></h6>
                            <p class="mb-1 text-muted">$<?= number_format((float) $p['precio_venta'], 0, ',', '.') ?></p>
                            <p class="mb-2"><small>Stock: <?= (int) $p['stock'] ?></small></p>
                            <button class="btn btn-sm btn-primary w-100 btn-agregar" data-id="<?= $p['id_producto'] ?>">Agregar</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-md-5">
        <h4>Carrito</h4>
        <div class="card">
            <ul class="list-group list-group-flush" id="carritoLista">
                <?php foreach ($carrito as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $item['id_producto'] ?>">
                        <div><?= esc($item['nombre']) ?> x<?= (int) $item['cantidad'] ?></div>
                        <div>
                            $<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?>
                            <button class="btn btn-sm btn-outline-danger ms-2 btn-quitar" data-id="<?= $item['id_producto'] ?>">x</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="card-body">
                <form action="/ventas/finalizar" method="post">
                    <div class="mb-2">
                        <label class="form-label">Tipo documento</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="boleta">Boleta</option>
                            <option value="factura">Factura</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Empresa (factura, opcional)</label>
                        <select name="empresa_fk" class="form-select">
                            <option value="">Venta a persona natural</option>
                            <?php foreach ($empresas as $e): ?>
                                <option value="<?= $e['id_empresa'] ?>"><?= esc($e['razon_social']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">RUT cliente (opcional)</label>
                        <input type="text" name="cliente_rut_fk" class="form-control" placeholder="12345678-9">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Dirección de despacho (opcional)</label>
                        <input type="text" name="direccion_despacho" class="form-control" placeholder="Si el cliente se la lleva, deja vacío">
                    </div>
                    <button type="submit" class="btn btn-success w-100" <?= empty($carrito) ? 'disabled' : '' ?>>Confirmar venta</button>
                </form>
                <form action="/ventas/carrito/vaciar" method="post" id="formVaciar" class="mt-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">Vaciar carrito</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('buscador').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.item-producto').forEach(el => {
        el.style.display = el.dataset.nombre.includes(q) ? '' : 'none';
    });
});

document.querySelectorAll('.btn-agregar').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const resp = await fetch('/ventas/carrito/agregar', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id_producto=' + id + '&cantidad=1'
        });
        const data = await resp.json();
        if (!data.ok) {
            alert(data.mensaje);
            return;
        }
        location.reload();
    });
});

document.querySelectorAll('.btn-quitar').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        await fetch('/ventas/carrito/quitar/' + id, { method: 'POST' });
        location.reload();
    });
});
</script>

<?= $this->endSection() ?>
