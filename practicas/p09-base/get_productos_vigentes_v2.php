<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php

/** SE CREA EL OBJETO DE CONEXION */
@$link = new mysqli('localhost', 'admin_tw', 'adylene', 'marketzone');
/** NOTA: con @ se suprime el Warning para gestionar el error por medio de código */

/** comprobamos la conexión */
if ($link->connect_errno) {
    die('Falló la conexión: ' . $link->connect_error . '<br/>');
}

if ($result = $link->query("SELECT * FROM productos WHERE eliminado !=1")) {

    $row = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($row as $num => $registro) {            
        foreach ($registro as $key => $value) {      
            $data[$num][$key] = utf8_encode($value);
        }
    }

    $result->free();
}

$link->close();


?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        img {
            height: 40px;
            width: auto;
        }
    </style>
    <script>
        function show() {
            // se obtiene el id de la fila donde está el botón presinado
            var rowId = event.target.parentNode.parentNode.id;

            // se obtienen los datos de la fila en forma de arreglo
            var data = document.getElementById(rowId).querySelectorAll(".row-data");
            /**
            querySelectorAll() devuelve una lista de elementos (NodeList) que 
            coinciden con el grupo de selectores CSS indicados.
            (ver: https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Selectors)

            En este caso se obtienen todos los datos de la fila con el id encontrado
            y que pertenecen a la clase "row-data".
            */
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
    <h3>PRODUCTO</h3>

    <br />

    <?php if (isset($row)) : ?>
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
                <?php foreach ($row as $value) : ?>
                    <tr>
                        <th scope="row"><?= $value['id'] ?></th>
                        <td><?= $value['nombre'] ?></td>
                        <td><?= $value['marca'] ?></td>
                        <td><?= $value['modelo'] ?></td>
                        <td><?= $value['precio'] ?></td>
                        <td><?= $value['unidades'] ?></td>
                        <td><?= $value['detalles'] ?></td>
                        <td><img src=<?= $value['imagen'] ?>></td>
                        <td><?= $value['eliminado'] ?></td>
                        <td>
                            <input type="button" value="Modificar"
                                onclick="send2form('<?= $value['id'] ?>','<?= $value['nombre'] ?>', '<?= $value['marca'] ?>', '<?= $value['modelo'] ?>', '<?= $value['precio'] ?>', '<?= $value['unidades'] ?>', '<?= $value['detalles'] ?>', '<?= $value['imagen'] ?>', '<?= $value['eliminado'] ?>')" />
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (!empty($id)) : ?>

        <script>
            alert('El ID del producto no existe');
        </script>

    <?php endif; ?>
    <script>
        function send2form(id, nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado) { //form) { 
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

            /*var urlForm = "formulario_productos_v2.php";
                var propId = "Id="+id;
                var propNombre = "Nombre="+nombre;
                var propMarca = "Marca="+marca;
                var propModelo = "Modelo="+modelo;
                window.open(urlForm+"?"+propId+"&"+propNombre+"&"+propMarca+"&"+propModelo);*/
        }
    </script>
</body>

</html>