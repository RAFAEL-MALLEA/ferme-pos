<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Notas de crédito</h3>

<table class="table table-striped bg-white">
    <thead><tr><th>#</th><th>Venta</th><th>Motivo</th><th>Monto</th><th>Emitida por</th><th>Fecha</th></tr></thead>
    <tbody>
    <?php foreach ($notas as $n): ?>
        <tr>
            <td><?= (int) $n['id_nota_credito'] ?></td>
            <td><a href="/ventas/ver/<?= $n['venta_fk'] ?>">#<?= (int) $n['venta_fk'] ?></a></td>
            <td><?= esc($n['motivo']) ?></td>
            <td>$<?= number_format((float) $n['monto'], 0, ',', '.') ?></td>
            <td><?= esc($n['nom_usuario']) ?></td>
            <td><?= esc($n['fecha']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
