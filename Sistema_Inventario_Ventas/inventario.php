<?php

session_start();

require_once "conexion.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// ======================================================
// BUSCADOR DINÁMICO
// ======================================================

// Obtener el término de búsqueda enviado por GET
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($busqueda != '') {

    // Consulta para buscar por nombre de producto
    // o por nombre de categoría
    $sql = "SELECT 
                p.id,
                p.nombre_producto,
                c.nombre_categoria,
                p.stock,
                p.precio
            FROM productos p
            INNER JOIN categorias c 
                ON p.categoria_id = c.id
            WHERE p.nombre_producto LIKE ?
               OR c.nombre_categoria LIKE ?
            ORDER BY p.id ASC";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Agregar los comodines %
    $parametro_busqueda = "%" . $busqueda . "%";

    // Vincular el parámetro dos veces
    $stmt->bind_param(
        "ss",
        $parametro_busqueda,
        $parametro_busqueda
    );

    // Ejecutar consulta
    $stmt->execute();

    // Obtener resultados
    $resultado = $stmt->get_result();

    // Cerrar sentencia
    $stmt->close();

} else {

    // ==================================================
    // MOSTRAR TODO EL INVENTARIO
    // ==================================================

    $sql = "SELECT 
                p.id,
                p.nombre_producto,
                c.nombre_categoria,
                p.stock,
                p.precio
            FROM productos p
            INNER JOIN categorias c 
                ON p.categoria_id = c.id
            ORDER BY p.id ASC";

    $resultado = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catálogo de Inventario</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="inventory-page">

<main class="inventory-container">

<div class="inventory-header">

    <!-- TÍTULO -->
    <h2>Catálogo de Inventario</h2>


    <!-- BOTÓN NUEVO PRODUCTO -->
    <a href="nuevo_producto.php" class="btn-nuevo">
        + Nuevo Producto
    </a>


    <!-- FORMULARIO DE BÚSQUEDA -->
    <form method="GET" action="inventario.php" class="search-form">

        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar producto o categoría..."
            value="<?php echo htmlspecialchars($busqueda); ?>"
        >

        <button type="submit">
            Buscar
        </button>

        <a href="inventario.php">
            Limpiar
        </a>

    </form>


    <!-- INFORMACIÓN DEL USUARIO -->
    <div class="user-actions">

        Usuario:

        <strong>
            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
        </strong>

        <a href="logout.php" class="btn-salir">
            Cerrar Sesión
        </a>

    </div>

</div>


<!-- =====================================================
     TABLA DE INVENTARIO
     ===================================================== -->

<div class="inventory-table-card">

<table class="inventory-table">

    <thead>

        <tr>

            <th>Código</th>

            <th>Nombre del Producto</th>

            <th>Categoría</th>

            <th>Stock</th>

            <th>Precio Unitario</th>

            <th>Acciones</th>

        </tr>

    </thead>


    <tbody>

    <?php

    // Verificar si existen resultados
    if ($resultado && $resultado->num_rows > 0) {

        // Recorrer los productos
        while ($fila = $resultado->fetch_assoc()) {

            // Determinar si el stock es bajo
            $claseStock =
                ($fila['stock'] < 10)
                ? "stock-bajo"
                : "";

    ?>

        <tr>

            <!-- CÓDIGO -->
            <td>
                <?php echo $fila['id']; ?>
            </td>


            <!-- NOMBRE DEL PRODUCTO -->
            <td>
                <?php echo htmlspecialchars($fila['nombre_producto']); ?>
            </td>


            <!-- CATEGORÍA -->
            <td>
                <?php echo htmlspecialchars($fila['nombre_categoria']); ?>
            </td>


            <!-- STOCK -->
            <td class="<?php echo $claseStock; ?>">

                <?php echo $fila['stock']; ?>

                unds.

            </td>


            <!-- PRECIO -->
            <td>

                $
                <?php echo number_format($fila['precio'], 2); ?>

            </td>


            <!-- ACCIONES -->
            <td>

                <!-- BOTÓN EDITAR -->
                <a 
                    href="editar_producto.php?id=<?php echo $fila['id']; ?>"
                    class="btn-editar"
                >
                    ✏️ Editar
                </a>


                <!-- BOTÓN ELIMINAR -->
                <a 
                    href="eliminar_producto.php?id=<?php echo $fila['id']; ?>"
                    class="btn-eliminar"
                    onclick="return confirm('¿Seguro que deseas eliminar el producto: <?php echo htmlspecialchars($fila['nombre_producto'], ENT_QUOTES); ?>?');"
                >
                    🗑️ Eliminar
                </a>

            </td>

        </tr>

    <?php

        }

    } else {

    ?>

        <!-- CUANDO NO HAY RESULTADOS -->
        <tr>

            <td colspan="6" style="text-align:center;">

                <?php

                if ($busqueda != '') {

                    echo "No se encontraron productos para: <strong>"
                        . htmlspecialchars($busqueda)
                        . "</strong>";

                } else {

                    echo "No hay productos registrados en el sistema.";

                }

                ?>

            </td>

        </tr>

    <?php

    }

    ?>

    </tbody>

</table>

</div>

</main>


</body>

</html>
