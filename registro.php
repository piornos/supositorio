<!DOCTYPE html>
<html lang="es">

<head>
    <title>Supositorio | Registro</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 x=%2250%%22 font-size=%2280%22 text-anchor=%22middle%22 transform=%22scale(-1, 1) translate(-100, 0)%22>💊</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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
        }

        .btn-custom {
            background-color: #475569; 
            color: white;
            border-radius: 50px;
            width: 100%;
            padding: 10px;
            border: none;
            font-weight: 400;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background-color: #334155;
            color: white;
        }

        .btn-registro {
            background-color: #10b981; 
        }
        .btn-registro:hover {
            background-color: #059669;
        }

        .form-control {
            border-radius: 50px !important;
            padding: 8px 18px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            border: 1px solid #e2e8f0;
        }

        h2.text-center {
            font-size: 1.5rem;
            font-weight: 400;
            color: #1e293b;
            margin-bottom: 25px !important;
        }

        .form-label.small {
            font-weight: 600;
            color: #64748b;
            margin-left: 12px;
            margin-bottom: 5px;
        }
        
        .link-volver {
            font-size: 0.75rem;
            color: #94a3b8;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        .link-volver:hover { color: #64748b; }

        #error-message {
            display: none;
            color: #991b1b;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            margin-top: 10px;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h2 class="text-center">Crear Cuenta</h2>
        <form id="registroForm" action="procesar_registro.php" method="POST">
            <label class="form-label small">Nuevo Usuario</label>
            <input type="text" name="usuario" class="form-control" placeholder="" autofocus required>
            
            <label class="form-label small">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="" required>
            
            <label class="form-label small">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" class="form-control" placeholder="" required>
            
            <button type="submit" class="btn btn-custom btn-registro">Registrar Usuario</button>
            
            <a href="index.php" class="link-volver">← Volver al acceso</a>
        </form>

        <div id="error-message" class="text-center">
            ⚠️ Las contraseñas no coinciden
        </div>

        <?php if (isset($_GET['error'])): ?>
            <p class="small mt-3 text-center" style="color: #991b1b; background-color: #ffffff; padding: 10px; border-radius: 50px;">
                <?php echo $_GET['error'] == 'duplicado' ? '⚠️ El usuario ya existe' : '❌ Error en los datos'; ?>
            </p>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>
            <p class="small mt-3 text-center" style="color: #166534; background-color: #ffffff; padding: 10px; border-radius: 50px;">
                ✅ Registro completado con éxito
            </p>
        <?php endif; ?>
    </div>

    <script>
        const form = document.getElementById('registroForm');
        const pass = document.getElementById('password');
        const confirmPass = document.getElementById('confirm_password');
        const errorDiv = document.getElementById('error-message');

        form.onsubmit = function(e) {
            if (pass.value !== confirmPass.value) {
                e.preventDefault();
                errorDiv.style.display = 'block';
                return false;
            }
            return true;
        };

        confirmPass.oninput = function() {
            errorDiv.style.display = 'none';
        };
    </script>
</body>
</html>
