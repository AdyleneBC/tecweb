<?php
include_once __DIR__ . '/database.php';
header('Content-Type: application/json; charset=utf-8');

// Lee JSON
$payload = file_get_contents('php://input');
if (!$payload) {
  http_response_code(400);
  echo json_encode(["error" => "Solicitud vacía."]); exit;
}
$data = json_decode($payload, true);
if (!is_array($data)) {
  http_response_code(400);
  echo json_encode(["error" => "JSON inválido."]); exit;
}

// Normaliza
$nombre   = trim($data['nombre']  ?? '');
$marca    = trim($data['marca']   ?? '');
$modelo   = trim($data['modelo']  ?? '');
$precio   = isset($data['precio']) ? floatval($data['precio']) : null;
$detalles = trim($data['detalles'] ?? '');
$unidades = isset($data['unidades']) ? intval($data['unidades']) : null;
$imagen   = trim($data['imagen']   ?? 'img/default.png');


$errors = [];


if ($nombre === '' || mb_strlen($nombre) > 100 || !preg_match('/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\s\-.,#()]+$/u', $nombre)) {
  $errors[] = "Nombre inválido.";
}
if ($marca === '' || $marca === 'NA') {
  $errors[] = "Marca inválida.";
}
if ($modelo === '' || mb_strlen($modelo) > 25 || !preg_match('/^[A-Za-z0-9\-]+$/', $modelo)) {
  $errors[] = "Modelo inválido.";
}
if ($precio === null || $precio <= 99.99) {
  $errors[] = "Precio inválido.";
}
if (mb_strlen($detalles) > 250) {
  $errors[] = "Detalles demasiado largos.";
}
if ($unidades === null || $unidades < 0) {
  $errors[] = "Unidades inválidas.";
}
if ($errors) {
  http_response_code(400);
  echo json_encode(["error" => implode(' ', $errors)]); exit;
}

//DUPLICADOS 
function existe($conexion, $sql, $types, ...$vals) {
  $stmt = $conexion->prepare($sql);
  if (!$stmt) return false;
  $stmt->bind_param($types, ...$vals);
  $ok = $stmt->execute();
  if (!$ok) { $stmt->close(); return false; }
  $stmt->store_result();
  $hay = $stmt->num_rows > 0;
  $stmt->close();
  return $hay;
}

if (existe($conexion,
  "SELECT id FROM productos WHERE eliminado = 0 AND nombre = ? AND marca = ? LIMIT 1",
  "ss", $nombre, $marca)) {
  echo json_encode(["error" => "Ya existe un producto con el mismo nombre y marca."]); exit;
}

if (existe($conexion,
  "SELECT id FROM productos WHERE eliminado = 0 AND marca = ? AND modelo = ? LIMIT 1",
  "ss", $marca, $modelo)) {
  echo json_encode(["error" => "Ya existe un producto con la misma marca y modelo."]); exit;
}


if (existe($conexion,
  "SELECT id FROM productos WHERE eliminado = 0 AND modelo = ? LIMIT 1",
  "s", $modelo)) {
  echo json_encode(["error" => "Ya existe un producto con el mismo modelo."]); exit;
}
//Agregué esto porque creo que mi id está mal, pero investigué y el auto inrement no se reinicia
$stmt = $conexion->prepare(
  "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado)
   VALUES (?, ?, ?, ?, ?, ?, ?, 0)"
);
if (!$stmt) {
  http_response_code(500);
  echo json_encode(["error" => "Error al preparar inserción: ".$conexion->error]); exit;
}
$stmt->bind_param("sssdsis",
  $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen
);
$ok = $stmt->execute();
if ($ok) {
  echo json_encode(["success" => "Producto agregado correctamente", "insert_id" => $conexion->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(["error" => "Error al insertar: ".$stmt->error]);
}
$stmt->close();
$conexion->close();
