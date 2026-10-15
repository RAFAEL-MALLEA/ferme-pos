<?= $this->extend('layouts/main') ?>
<?= $this->section('contenido') ?>

<h3 class="mb-4"><?= $usuario ? 'Editar usuario' : 'Nuevo usuario' ?></h3>

<form action="<?= $usuario ? '/usuarios/actualizar/' . $usuario['id_usuario'] : '/usuarios/guardar' ?>" method="post" class="card card-body" style="max-width:600px;">

    <?php if (! $usuario): ?>
    <div class="mb-3">
        <label class="form-label">RUT</label>
        <input type="text" name="rut" class="form-control" placeholder="12345678-9" required>
    </div>
    <div class="row">
        <div class="col mb-3"><label class="form-label">Nombres</label><input type="text" name="nombres" class="form-control" required></div>
        <div class="col mb-3"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" required></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Comuna</label>
        <select name="comuna_fk" class="form-select" required>
            <?php foreach ($comunas as $c): ?>
                <option value="<?= $c['id_comuna'] ?>"><?= esc($c['nombre']) ?> (<?= esc($c['nombre_region']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="row">
        <div class="col mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control"></div>
        <div class="col mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
    </div>
    <div class="mb-3">
        <label class="form-label">Nombre de usuario</label>
        <input type="text" name="nom_usuario" class="form-control" required>
    </div>
    <?php else: ?>
        <p><strong>RUT:</strong> <?= esc($datosPersona['rut']) ?> — <strong>Usuario:</strong> <?= esc($usuario['nom_usuario']) ?></p>
        <div class="row">
            <div class="col mb-3"><label class="form-label">Nombres</label><input type="text" name="nombres" class="form-control" value="<?= esc($datosPersona['nombres']) ?>" required></div>
            <div class="col mb-3"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" value="<?= esc($datosPersona['apellidos']) ?>" required></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Comuna</label>
            <select name="comuna_fk" class="form-select" required>
                <?php foreach ($comunas as $c): ?>
                    <option value="<?= $c['id_comuna'] ?>" <?= $c['id_comuna'] == $datosPersona['comuna_fk'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <div class="col mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" value="<?= esc($datosPersona['telefono']) ?>"></div>
            <div class="col mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= esc($datosPersona['email']) ?>"></div>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Rol</label>
        <select name="nivel_acceso_fk" class="form-select" required>
            <?php foreach ($niveles as $n): ?>
                <option value="<?= $n['id_nivel_acceso'] ?>" <?= (isset($usuario) && $usuario['nivel_acceso_fk'] == $n['id_nivel_acceso']) ? 'selected' : '' ?>>
                    <?= esc($n['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label"><?= $usuario ? 'Nueva contraseña (opcional)' : 'Contraseña' ?></label>
        <input type="password" name="contrasena" class="form-control" <?= $usuario ? '' : 'required' ?> minlength="6">
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="/usuarios" class="btn btn-link">Cancelar</a>
</form>

<?= $this->endSection() ?>
