<?php
// Enviar encabezado correcto para XHTML
header("Content-Type: application/xhtml+xml; charset=UTF-8");

// Verificar si se recibió el parámetro "tope"
if (!isset($_GET['tope'])) {
    die('<p style="color: red; text-align: center;">Parámetro "tope" no detectado...</p>');
}

$tope = intval($_GET['tope']); // Convertir a entero para evitar SQL Injection
$productos = [];

// Conectar a la base de datos
@$link = new mysqli('localhost', 'admin_tw', 'adylene', 'marketzone');

// Comprobar conexión
if ($link->connect_errno) {
    die('<p style="color: red; text-align: center;">Error en la conexión: ' . htmlspecialchars($link->connect_error) . '</p>');
}

// Ejecutar consulta
if ($result = $link->query("SELECT * FROM productos WHERE unidades <= $tope")) {
    $productos = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}
$link->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
        crossorigin="anonymous" />
    <script>
        function show() {
            // se obtiene el id de la fila donde está el botón presionado
            var rowId = event.target.parentNode.parentNode.id;

            // se obtienen los datos de la fila en forma de arreglo
            var data = document.getElementById(rowId).querySelectorAll(".row-data");

            var id = data[0].innerHTML;
            var nombre = data[1].innerHTML;
            var marca = data[2].innerHTML;
            var modelo = data[3].innerHTML;
            var precio = data[4].innerHTML;
            var unidades = data[5].innerHTML;
            var detalles = data[6].innerHTML;
            var imagen = data[7].innerHTML;
            var eliminado = data[8].innerHTML;

            send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado);
        }
    </script>
</head>
<body>
    <h3 style="text-align: center;">Productos</h3>
    <br />

    <?php if (count($productos) > 0): ?>
        <table class="table table-bordered" style="width: 90%; margin: auto;">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Detalles</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Eliminado</th>
                    <th scope="col">Modificar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($producto['id']) ?></th>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['marca']) ?></td>
                        <td><?= htmlspecialchars($producto['modelo']) ?></td>
                        <td><?= htmlspecialchars($producto['precio']) ?></td>
                        <td><?= htmlspecialchars($producto['unidades']) ?></td>
                        <td><?= htmlspecialchars($producto['detalles']) ?></td>
                        <td><img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="Imagen" style="width: 100px; height: auto;" /></td>
                        <td><?= htmlspecialchars($producto['eliminado'])?></td>
                        <td>
                            <input type="button" value="Modificar" 
                            onclick="send2form('<?= htmlspecialchars($producto['id']) ?>', 
                                                '<?= htmlspecialchars($producto['nombre']) ?>', 
                                                '<?= htmlspecialchars($producto['marca']) ?>', 
                                                '<?= htmlspecialchars($producto['modelo']) ?>', 
                                                '<?= htmlspecialchars($producto['precio']) ?>', 
                                                '<?= htmlspecialchars($producto['unidades']) ?>', 
                                                '<?= htmlspecialchars($producto['detalles']) ?>', 
                                                '<?= htmlspecialchars($producto['imagen']) ?>', 
                                                '<?= htmlspecialchars($producto['eliminado']) ?>')" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color: red; text-align: center;">No hay productos con unidades menores o iguales a <?= $tope ?>.</p>
    <?php endif; ?>

    <script>
        function send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado) {
            var form = document.createElement("form");

            var idIn = document.createElement("input");
            idIn.type = 'hidden';
            idIn.name = 'id';
            idIn.value = id;
            form.appendChild(idIn);

            var nombreIn = document.createElement("input");
            nombreIn.type = 'hidden';
            nombreIn.name = 'nombre';
            nombreIn.value = nombre;
            form.appendChild(nombreIn);

            var marcaIn = document.createElement("input");
            marcaIn.type = 'hidden';
            marcaIn.name = 'marca';
            marcaIn.value = marca;
            form.appendChild(marcaIn);

            var modeloIn = document.createElement("input");
            modeloIn.type = 'hidden';
            modeloIn.name = 'modelo';
            modeloIn.value = modelo;
            form.appendChild(modeloIn);

            var precioIn = document.createElement("input");
            precioIn.type = 'hidden';
            precioIn.name = 'precio';
            precioIn.value = precio;
            form.appendChild(precioIn);

            var unidadesIn = document.createElement("input");
            unidadesIn.type = 'hidden';
            unidadesIn.name = 'unidades';
            unidadesIn.value = unidades;
            form.appendChild(unidadesIn);

            var detallesIn = document.createElement("input");
            detallesIn.type = 'hidden';
            detallesIn.name = 'detalles';
            detallesIn.value = detalles;
            form.appendChild(detallesIn);

            var imagenIn = document.createElement("input");
            imagenIn.type = 'hidden';
            imagenIn.name = 'imagen';
            imagenIn.value = imagen;
            form.appendChild(imagenIn);

            var eliminadoIn = document.createElement("input");
            eliminadoIn.type = 'hidden';
            eliminadoIn.name = 'eliminado';
            eliminadoIn.value = eliminado;
            form.appendChild(eliminadoIn);

            form.method = 'POST';
            form.action = 'formulario_productos_v2.php';  

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
