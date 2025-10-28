<?php
include_once __DIR__ . '/database.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_POST['id'])) {
    echo json_encode(["error" => "Falta id"]);
    exit;
}
$id = intval($_POST['id']);

$sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen
        FROM productos
        WHERE id = ? AND eliminado = 0
        LIMIT 1";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["error" => "No existe"], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => $conexion->error], JSON_PRETTY_PRINT);
}
$stmt->close();
$conexion->close();
