<?php
session_start();

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
require_once "conexion.php";

// 1. Contar productos registrados
$sql_productos = "SELECT COUNT(*) AS total FROM productos";
$resultado_productos = $conn->query($sql_productos);
$total_productos = $resultado_productos->fetch_assoc()['total'];

// 2. Calcular unidades totales en stock
$sql_stock = "SELECT COALESCE(SUM(stock), 0) AS total FROM productos";
$resultado_stock = $conn->query($sql_stock);
$total_stock = $resultado_stock->fetch_assoc()['total'];

// 3. Calcular el valor total del inventario
$sql_valor = "SELECT COALESCE(SUM(stock * precio), 0) AS total FROM productos";
$resultado_valor = $conn->query($sql_valor);
$valor_inventario = $resultado_valor->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Sistema de Inventario</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, 'Times New Roman', serif;
            background: #fff3f6;
            margin: 0;
            padding: 30px;
            color: #4a2635;
        }

        .contenedor {
            max-width: 1200px;
            margin: auto;
        }

        /* Barra de navegación */
        .navbar {
            background: #fffafb;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(152, 91, 114, 0.14);
            border-radius: 10px;
            border: 1px solid #efd6a8;
        }

        .navbar h1 {
            margin: 0;
            font-size: 22px;
        }

        .btn-salir {
            background: #b06b7f;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        /* Contenedor Flexbox para las tarjetas */
        .tarjetas-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        /* Diseño de cada métrica */
        .tarjeta {
            background: #fffafb;
            flex: 1;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(152, 91, 114, 0.10);
            text-align: center;
            border-top: 5px solid #c3974c;
        }

        .tarjeta.verde {
            border-top-color: #d68ea7;
        }

        .tarjeta.naranja {
            border-top-color: #c3974c;
        }

        .tarjeta h3 {
            color: #8d5b6b;
            margin: 0 0 10px 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .tarjeta .numero {
            font-size: 32px;
            font-weight: bold;
            color: #4a2635;
            margin: 0;
        }

        /* Menú de accesos rápidos */
        .menu-modulos {
            display: flex;
            gap: 20px;
        }

        .modulo {
            background: #c77d98;
            color: white;
            flex: 1;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 18px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .modulo:hover {
            background: #b86884;
        }

        /* Adaptación para celulares */
        @media (max-width: 768px) {
            .tarjetas-container,
            .menu-modulos {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="contenedor">

    <!-- Barra superior -->
    <div class="navbar">

        <h1>Panel de Control - Inventario</h1>

        <a href="logout.php" class="btn-salir">
            Cerrar sesión
        </a>

    </div>


    <!-- Tarjetas de métricas -->
    <div class="tarjetas-container">

        <!-- Productos registrados -->
        <div class="tarjeta">

            <h3>Productos registrados</h3>

            <p class="numero">
                <?php echo $total_productos; ?>
            </p>

        </div>


        <!-- Stock total -->
        <div class="tarjeta verde">

            <h3>Unidades en stock</h3>

            <p class="numero">
                <?php echo $total_stock; ?>
            </p>

        </div>


        <!-- Valor del inventario -->
        <div class="tarjeta naranja">

            <h3>Valor del inventario</h3>

            <p class="numero">
                $<?php echo number_format($valor_inventario, 2); ?>
            </p>

        </div>

    </div>


    <!-- Accesos rápidos -->
    <div class="menu-modulos">

        <a href="inventario.php" class="modulo">
            Ir al Catálogo de Inventario
        </a>

        <a href="proveedores.php" class="modulo modulo-proveedores">
            🚚 Módulo de Proveedores
        </a>

        <a href="nueva_compra.php" class="modulo" style="background: #c3974c;">
            Registrar ingreso de mercadería
        </a>

        <a href="index.php" class="modulo">
            Página Principal
        </a>

    </div>

</div>

</body>

</html>
