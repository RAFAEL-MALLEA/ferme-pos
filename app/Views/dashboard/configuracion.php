<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4">Configuración del negocio</h3>

<form action="/configuracion/guardar" method="post" class="card card-body" style="max-width:500px;">
    <div class="mb-3">
        <label class="form-label">Nombre de la empresa</label>
        <input type="text" name="nombre_empresa" class="form-control" value="<?= esc($configuracion['nombre_empresa']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control" value="<?= esc($configuracion['direccion']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="<?= esc($configuracion['telefono']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

<?= $this->endSection() ?>
