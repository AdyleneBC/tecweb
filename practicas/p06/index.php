<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 4</title>
    <?php
    include 'src/funciones.php';
    ?>
</head>

<body>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
    funcion1();
    ?>

    <h2>Ejercicio 2</h2>
    <?php
    ejercicio2();
    ?>

    <h2>Ejercicio 3</h2>
    <?php
    echo "<p><b>Ejercicio 3 usando while</b></p>";
    ejercicio3_while();
    echo "<br>";
    echo "<p><b>Ejercicio 3 usando do while</b></p>";
    ejercicio3_do_while();
    ?>

    <h2>Ejercicio 4</h2>
    <?php
    ejercicio4();
    ?>

    <h2>Ejercicio 5</h2>
    <form method="POST" action="">
        <fieldset>
            <legend>Llena el formulario</legend><br>

            <label>Edad: </label><input type="number" name="edad" required><br><br>

            <p>Sexo:</p>
            <input type="radio" name="sexo" value="femenino" required> <label>Femenino</label>
            <input type="radio" name="sexo" value="masculino"> <label>Masculino</label>
            <input type="radio" name="sexo" value="otro"> <label>Otro</label>
            <br><br>

            <input type="submit" value="Enviar">
        </fieldset>
    </form>

</body>

</html>