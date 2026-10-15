<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Nota de crédito — Venta #<?= (int) $venta['id_venta'] ?></h3>

<table class="table bg-white mb-4">
    <thead><tr><th>Producto</th><th>Cantidad</th><th>Subtotal</th></tr></thead>
    <tbody>
    <?php foreach ($detalles as $d): ?>
        <tr>
            <td><?= esc($d['producto_nombre']) ?></td>
            <td><?= (int) $d['cantidad'] ?></td>
            <td>$<?= number_format((float) $d['subtotal'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<form action="/notas-credito/guardar/<?= $venta['id_venta'] ?>" method="post" class="card card-body" style="max-width:500px;">
    <div class="mb-3">
        <label class="form-label">Motivo</label>
        <textarea name="motivo" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Monto a devolver</label>
        <input type="number" step="0.01" name="monto" class="form-control" max="<?= $venta['total'] ?>" required>
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="reponer_stock" value="1" class="form-check-input" id="reponer" checked>
        <label class="form-check-label" for="reponer">Reponer stock de los productos devueltos</label>
    </div>
    <button type="submit" class="btn btn-primary">Generar nota de crédito</button>
</form>

<?= $this->endSection() ?>
