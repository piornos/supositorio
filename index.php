<!DOCTYPE html>
<html lang="es">

<head>
    <title>Supositorio | Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 x=%2250%%22 font-size=%2280%22 text-anchor=%22middle%22 transform=%22scale(-1, 1) translate(-100, 0)%22>💊</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@500;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e2e8f0 !important;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            background: white;
            padding: 35px;
            border-radius: 6px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 350px;
            margin: auto;
            font-weight: 300;
        }

        .btn-custom {
            background-color: #475569;
            color: white;
            border-radius: 50px;
            width: 100%;
            padding: 10px;
            border: none;
            font-weight: 400;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background-color: #334155;
            color: white;
        }

        .form-control {
            border-radius: 50px !important;
            padding: 8px 18px;
            margin-bottom: 15px;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(71, 85, 105, 0.1);
            border-color: #94a3b8;
        }

        h2.text-center {
            font-family: 'Inter', sans-serif;
            font-size: 1.5rem;
            font-weight: 400;
            color: #1e293b;
            margin-bottom: 25px !important;
        }

        .form-label.small {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: #64748b;
            margin-left: 12px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h2 class="text-center">Acceso</h2>
        <form action="validar.php" method="POST">
            <label class="form-label small">Usuario</label>
            <input type="text" name="usuario" class="form-control" placeholder="Usuario" autofocus required>
            <label class="form-label small">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
            <button type="submit" class="btn btn-custom">Entrar</button>
            <div class="text-center mt-3">
    <a href="registro.php" style="font-size: 0.75rem; color: #64748b; text-decoration: none;">Crea tu usuario</a>
</div>
<?php if (isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
    <div style="background-color: #ffffff; color: #166534; padding: 12px; border-radius: 50px; border: 1px solid #ffffff; margin-bottom: 20px; text-align: center; font-size: 0.85rem; font-weight: 600;">
        ✅ ¡Registro correcto! Ya puedes entrar.
    </div>
<?php endif; ?>
            <?php if (isset($_GET['error'])) echo '<p class="text-danger small mt-3 text-center">Datos incorrectos</p>'; ?>
        </form>
    </div>
</body>

</html>