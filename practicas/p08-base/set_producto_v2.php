<?php

/** SE CREA EL OBJETO DE CONEXION */
@$link = new mysqli('localhost', 'admin_tw', 'adylene', 'marketzone');

/** comprobar la conexión */
if ($link->connect_errno) {
    die('Falló la conexión: ' . $link->connect_error . '<br/>');
}

/** Opcional: charset para acentos/ñ */
$link->set_charset('utf8mb4');


$nombre = $_POST['nombre'];
$marca  = $_POST['marca'];
$modelo = $_POST['modelo'];
$precio = $_POST['precio'];
$detalles = $_POST['detalles'];
$unidades = $_POST['unidades'];
$imagen   = $_POST['imagen'];

/** 2) Verificar duplicado (nombre+marca+modelo) */
$sql_verificar = "SELECT COUNT(*) AS total
                  FROM productos
                  WHERE nombre = '{$nombre}'
                    AND marca  = '{$marca}'
                    AND modelo = '{$modelo}'";
$resultado = mysqli_query($link, $sql_verificar);

if ($resultado) {
    list($total) = mysqli_fetch_row($resultado);

    if ((int)$total > 0) {
        /* mensaje de error por existencia */
        echo "<p>Error: Ya existe un producto con el mismo nombre, marca y modelo.</p>";
    } else {
        /** 3) Insertar con column list */
        /*$sql_insertar = "INSERT INTO productos
VALUES (NULL, '{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";*/

 $sql_insertar = "INSERT INTO productos
            (nombre, marca, modelo, precio, detalles, unidades, imagen)
            VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}')";

        if (mysqli_query($link, $sql_insertar)) {
            $id = $link->insert_id;

            /* 4) Resumen XHTML simple */
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">';
            echo '<head><meta http-equiv="content-type" content="text/html; charset=utf-8" />';
            echo '<title>Registro Completado</title>';
            echo '<style type="text/css">
                    body {margin:20px; background:#F3F7ED; font-family:Verdana,Helvetica,sans-serif; font-size:90%;}
                    h1 {color:#005825; border-bottom:1px solid #005825;}
                    ul {line-height:1.6;}
                  </style></head><body>';
            echo '<h1>Producto insertado con ID: ' . htmlspecialchars((string)$id) . '</h1>';
            echo '<ul>';
            echo '<li><b>Nombre:</b> '   . htmlspecialchars($nombre)   . '</li>';
            echo '<li><b>Marca:</b> '    . htmlspecialchars($marca)    . '</li>';
            echo '<li><b>Modelo:</b> '   . htmlspecialchars($modelo)   . '</li>';
            echo '<li><b>Precio:</b> '   . htmlspecialchars(number_format((float)$precio, 2, ".", "")) . '</li>';
            echo '<li><b>Unidades:</b> ' . htmlspecialchars((string)$unidades) . '</li>';
            echo '<li><b>Imagen:</b> '   . htmlspecialchars($imagen)   . '</li>';
            echo '</ul>';
            echo '</body></html>';
        } else {
            echo "Error al registrar el producto: " . mysqli_error($link);
        }
    }
} else {
    echo "Error al verificar duplicado: " . mysqli_error($link);
}

$link->close();
