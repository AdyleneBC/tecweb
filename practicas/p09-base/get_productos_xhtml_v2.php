<?php
header("Content-Type: application/xhtml+xml; charset=UTF-8");

if (!isset($_GET['tope'])) {
    die('<p style="color: red; text-align: center;">Verifique que el parametro tope sea correcto</p>');
}

$tope = intval($_GET['tope']); 
$productos = [];

/** SE CREA EL OBJETO DE CONEXION */
@$link = new mysqli('localhost', 'admin_tw', 'adylene', 'marketzone');

/** comprobar la conexión */
if ($link->connect_errno) {
    die('<p style="color: red; text-align: center;">Error en la conexión: ' . htmlspecialchars($link->connect_error) . '</p>');
}

/** Crear consulta */
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
            var rowId = event.target.parentNode.parentNode.id;

            
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
    <h3 style="text-align: center;">Tabla de Productos (Sneakers)</h3>
    <br />

    <?php if (count($productos) > 0): ?>
        <table class="table">
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
        <p style="color: red; text-align: center;"> No hay productos vigentes para mostrar con el tope: <?= $tope ?>.</p>
    <?php endif; ?>

    <script>
        function send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado) {
            var form = document.createElement("form");

            var propId = document.createElement("input");
            propId.type = 'hidden';
            propId.name = 'id';
            propId.value = id;
            form.appendChild(propId);

            var propNombre = document.createElement("input");
            propNombre.type = 'hidden';
            propNombre.name = 'nombre';
            propNombre.value = nombre;
            form.appendChild(propNombre);

            var propMarca = document.createElement("input");
            propMarca.type = 'hidden';
            propMarca.name = 'marca';
            propMarca.value = marca;
            form.appendChild(propMarca);

            var propModelo = document.createElement("input");
            propModelo.type = 'hidden';
            propModelo.name = 'modelo';
            propModelo.value = modelo;
            form.appendChild(propModelo);

            var propPrecio = document.createElement("input");
            propPrecio.type = 'hidden';
            propPrecio.name = 'precio';
            propPrecio.value = precio;
            form.appendChild(propPrecio);

            var propUnidades = document.createElement("input");
            propUnidades.type = 'hidden';
            propUnidades.name = 'unidades';
            propUnidades.value = unidades;
            form.appendChild(propUnidades);

            var propDetalles = document.createElement("input");
            propDetalles.type = 'hidden';
            propDetalles.name = 'detalles';
            propDetalles.value = detalles;
            form.appendChild(propDetalles);

            var propImagen = document.createElement("input");
            propImagen.type = 'hidden';
            propImagen.name = 'imagen';
            propImagen.value = imagen;
            form.appendChild(propImagen);

            var propEliminado = document.createElement("input");
            propEliminado.type = 'hidden';
            propEliminado.name = 'eliminado';
            propEliminado.value = eliminado;
            form.appendChild(propEliminado);

            form.method = 'POST';
            form.action = 'formulario_productos_v2.php';  

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
