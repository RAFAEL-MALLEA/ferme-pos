<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3>Orden de compra #<?= (int) $orden['id_orden_compra'] ?></h3>
<p class="text-muted">Estado: <?= esc($orden['estado']) ?> · Fecha: <?= esc($orden['fecha']) ?></p>

<table class="table bg-white">
    <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unit.</th><th>Subtotal</th></tr></thead>
    <tbody>
    <?php foreach ($detalles as $d): ?>
        <tr>
            <td><?= esc($d['producto_nombre']) ?></td>
            <td><?= (int) $d['cantidad'] ?></td>
            <td>$<?= number_format((float) $d['precio_unitario'], 0, ',', '.') ?></td>
            <td>$<?= number_format((float) $d['subtotal'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot><tr><th colspan="3" class="text-end">Total</th><th>$<?= number_format((float) $orden['total'], 0, ',', '.') ?></th></tr></tfoot>
</table>

<a href="/compras" class="btn btn-link">Volver</a>

<?= $this->endSection() ?>
