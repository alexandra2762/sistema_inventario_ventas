<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Proveedor</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5f4050;
            background: #fff1f6;
        }

        .form-container {
            width: 100%;
            max-width: 620px;
            padding: 32px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .12);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 22px;
            color: #9d4f70;
            font-weight: 700;
            text-decoration: none;
        }

        .back-link:hover { color: #c96488; }

        h1 {
            margin: 0 0 26px;
            color: #9d4f70;
            font-size: 1.75rem;
        }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 7px;
            color: #885468;
            font-weight: 700;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #edb9ca;
            border-radius: 8px;
            font: inherit;
            color: #5f4050;
            background: #fffafd;
        }

        textarea { min-height: 110px; resize: vertical; }
        input:focus, textarea:focus {
            outline: none;
            border-color: #d87093;
            box-shadow: 0 0 0 3px rgba(216, 112, 147, .16);
        }

        .error {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 8px;
            color: #9f1239;
            background: #ffe4e6;
        }

        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            color: #fff;
            background: #d9779a;
            font: inherit;
            font-weight: 700;
        }

        button:hover { background: #c96488; }

        @media (max-width: 520px) {
            body { padding: 16px; }
            .form-container { padding: 24px 20px; }
        }
    </style>
</head>
<body>
    <main class="form-container">
        <a href="proveedores.php" class="back-link">← Volver al Catálogo</a>
        <h1>Registrar Proveedor</h1>

        <?php if ($error === 'empresa') { ?>
            <div class="error">El nombre de la empresa es obligatorio.</div>
        <?php } elseif ($error === 'guardar') { ?>
            <div class="error">No fue posible guardar el proveedor. Inténtalo nuevamente.</div>
        <?php } ?>

        <form action="guardar_proveedor.php" method="POST">
            <div class="form-group">
                <label for="empresa">Nombre de la Empresa</label>
                <input type="text" id="empresa" name="empresa" placeholder="Ej. Tech Data S.A." required>
            </div>

            <div class="form-group">
                <label for="contacto">Nombre del Contacto</label>
                <input type="text" id="contacto" name="contacto" placeholder="Ej. Juan Pérez (Ventas)">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ej. 2222-3333">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección Física</label>
                <textarea id="direccion" name="direccion"></textarea>
            </div>

            <button type="submit">Guardar Proveedor</button>
        </form>
    </main>
</body>
</html>
