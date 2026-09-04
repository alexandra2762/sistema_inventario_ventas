<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: Dashboard.php');
    exit();
}

$usuario_id = (int) $_SESSION['user_id'];
$proveedor_id = filter_input(INPUT_POST, 'proveedor_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$precio_compra = filter_input(INPUT_POST, 'precio_compra', FILTER_VALIDATE_FLOAT);

if (!$proveedor_id || !$producto_id || !$cantidad || $precio_compra === false || $precio_compra <= 0) {
    header('Location: nueva_compra.php?error=datos');
    exit();
}

$total_compra = $cantidad * $precio_compra;

try {
    $conn->begin_transaction();

    $stmt_compra = $conn->prepare('INSERT INTO compras (proveedor_id, usuario_id, total) VALUES (?, ?, ?)');
    $stmt_compra->bind_param('iid', $proveedor_id, $usuario_id, $total_compra);
    $stmt_compra->execute();
    $id_nueva_compra = $conn->insert_id;
    $stmt_compra->close();

    $stmt_detalle = $conn->prepare('INSERT INTO detalle_compras (compra_id, producto_id, cantidad, precio_compra) VALUES (?, ?, ?, ?)');
    $stmt_detalle->bind_param('iiid', $id_nueva_compra, $producto_id, $cantidad, $precio_compra);
    $stmt_detalle->execute();
    $stmt_detalle->close();

    $stmt_stock = $conn->prepare('UPDATE productos SET stock = stock + ? WHERE id = ?');
    $stmt_stock->bind_param('ii', $cantidad, $producto_id);
    $stmt_stock->execute();
    $stmt_stock->close();

    $conn->commit();
    header('Location: Dashboard.php?compra=guardada');
    exit();
} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    die('Error al registrar la compra: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
