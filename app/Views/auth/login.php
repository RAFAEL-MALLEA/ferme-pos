<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar - Ferme POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center" style="min-height:100vh;">
<div class="container" style="max-width: 400px;">
    <div class="card shadow">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">🔧 Ferme POS</h3>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <form method="post" action="/login">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="nom_usuario" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>
            <p class="text-muted text-center mt-3 mb-0" style="font-size:.85rem;">
                Usuario inicial: <code>admin</code> / <code>Admin#2024</code>
            </p>
        </div>
    </div>
</div>
</body>
</html>
