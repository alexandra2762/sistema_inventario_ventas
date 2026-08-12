<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

$sql = "SELECT * FROM proveedores ORDER BY id ASC";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Proveedores</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="inventory-page">
<main class="inventory-container">
    <div class="inventory-header suppliers-header">
        <h2>Catálogo de Proveedores</h2>
        <div class="supplier-header-actions">
            <a href="nuevo_proveedor.php" class="btn-nuevo">+ Nuevo Proveedor</a>
            <a href="Dashboard.php" class="btn-dashboard">Volver al Dashboard</a>
        </div>
        <div class="user-actions">
            Usuario:
            <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
            <a href="logout.php" class="btn-salir">Cerrar Sesión</a>
        </div>
    </div>

    <div class="inventory-table-card">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresa</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0) { ?>
                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($fila['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($fila['nombre_empresa'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($fila['contacto'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($fila['telefono'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($fila['direccion'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="empty-table-message">No hay proveedores registrados en el sistema.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
