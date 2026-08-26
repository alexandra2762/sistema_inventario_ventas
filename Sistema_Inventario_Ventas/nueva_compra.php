<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
require_once 'conexion.php';

$proveedores = $conn->query('SELECT id, nombre_empresa FROM proveedores ORDER BY nombre_empresa ASC');
$productos = $conn->query('SELECT id, nombre_producto FROM productos ORDER BY nombre_producto ASC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar compra</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 30px; color: #0f172a; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, .08); }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; color: #334155; }
        select, input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #10b981; color: #fff; border: 0; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background: #059669; }
        .btn-volver { display: inline-block; margin-bottom: 20px; color: #64748b; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <main class="container">
        <a href="Dashboard.php" class="btn-volver">← Volver al Dashboard</a>
        <h2>Ingresar nueva mercadería</h2>
        <form action="guardar_compra.php" method="POST">
            <div class="form-group">
                <label for="proveedor_id">Proveedor:</label>
                <select id="proveedor_id" name="proveedor_id" required>
                    <option value="">-- Seleccione un proveedor --</option>
                    <?php while ($proveedor = $proveedores->fetch_assoc()): ?>
                        <option value="<?= (int) $proveedor['id'] ?>"><?= htmlspecialchars($proveedor['nombre_empresa'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="producto_id">Producto a ingresar:</label>
                <select id="producto_id" name="producto_id" required>
                    <option value="">-- Seleccione el producto --</option>
                    <?php while ($producto = $productos->fetch_assoc()): ?>
                        <option value="<?= (int) $producto['id'] ?>"><?= htmlspecialchars($producto['nombre_producto'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad de unidades:</label>
                <input id="cantidad" type="number" name="cantidad" min="1" required>
            </div>
            <div class="form-group">
                <label for="precio_compra">Precio de compra unitario ($):</label>
                <input id="precio_compra" type="number" name="precio_compra" step="0.01" min="0.01" required>
            </div>
            <button type="submit">Procesar y guardar compra</button>
        </form>
    </main>
</body>
</html>
