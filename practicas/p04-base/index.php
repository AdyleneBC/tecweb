<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 3</title>
</head>

<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar, $_7var, myvar, $myvar, $var7, $_element1, $house*5</p>
    <?php
    //AQUI VA MI CÓDIGO PHP
    $_myvar;
    $_7var;
    //myvar;       // Inválida
    $myvar;
    $var7;
    $_element1;
    //$house*5;     // Invalida

    echo '<h4>Respuesta:</h4>';

    echo '<ul>';
    echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
    echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
    echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
    echo '<li>$myvar es válida porque inicia con una letra.</li>';
    echo '<li>$var7 es válida porque inicia con una letra.</li>';
    echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
    echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
    echo '</ul>';
    ?>

    <h2>Ejercicio 2</h2>
    <p>Proporcionar los valores de $a, $b, $c como sigue:</p>
    <p>$a = “ManejadorSQL”;</p>
    <p>$b = 'MySQL’;</p>
    <p>$c = &$a;</p>

    <?php

    $a = "ManejadorSQL";
    $b = "MySQL";
    $c = &$a;

    echo "<p>Valor de a: $a</p>";
    echo "<p>Valor de b: $b</p>";
    echo "<p>Valor de c: $c</p>";

    $a = "PHP server";
    $b = &$a;

    echo "<p>Valor de a: $a</p>";
    echo "<p>Valor de b: $b</p>";
    echo "<p>Valor de c: $c</p>";

    echo '<h4>Respuesta:</h4>';
    echo '<p>Las variables $a, $b y $c comparten una referencia, por lo tanto, al cambiar $a, también cambian $b y $c.</p>';


    ?>

    <h2>Ejercicio 3</h2>
    <p>Muestra el contenido de cada variable inmediatamente después de cada asignación,
        verificar la evolución del tipo de estas variables (imprime todos los componentes de los
        arreglo):</p>

    <?php
    $a = "PHP5";
    $z[] = &$a;
    $b = "5a version de PHP";
    @$c = $b * 10;
    $a .= $b;
    $b *= $c;
    $z[0] = "MySQL";

    echo "<p>\$a = " . htmlspecialchars($a) . "</p>";
    echo "<p>\$b = " . htmlspecialchars($b) . "</p>";
    echo "<p>\$c = " . htmlspecialchars($c) . "</p>";
    echo "<p>\$z = ";
    print_r($z);
    echo "</p>";
    ?>

    <h2>Ejercicio 4</h2>
    <?php
    unset($a, $b, $c, $z);
    $a = "PHP5";
    $z[] = &$a;
    $b = "5a version de PHP";
    @$c = $b * 10;

    echo "<p>\$a = " . var_export($GLOBALS['a'], true) . "</p>";
    echo "<p>\$b = " . var_export($GLOBALS['b'], true) . "</p>";
    echo "<p>\$c = " . var_export($GLOBALS['c'], true) . "</p>";
    ?>

    <h2>Ejercicio 5</h2>
    <?php
    $a = "7 personas";
    $b = (int) $a;
    $a = "9E3";
    $c = (float) $a;

    echo "<p>\$a = " . htmlspecialchars($a) . "</p>";
    echo "<p>\$b = " . htmlspecialchars($b) . "</p>";
    echo "<p>\$c = " . htmlspecialchars($c) . "</p>";
    ?>

    <h2>Ejercicio 6</h2>
    <?php
    $a = "0";
    $b = "TRUE";
    $c = FALSE;
    $d = ($a or $b);
    $e = ($a and $c);
    $f = ($a xor $b);

    echo "<pre>";
    var_dump($a, $b, $c, $d, $e, $f);
    echo "</pre>";

    // Convertir booleano a string legible
    echo "c como texto: " . var_export($c, true) . "<br>";
    echo "e como texto: " . var_export($e, true) . "<br>";

    unset($a, $b, $c, $d, $e, $f);
    ?>

    <h2>Ejercicio 7</h2>
    <ul>
        <li><strong>Versión de Apache y PHP:</strong> <?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE']); ?></li>
        <li><strong>Sistema Operativo del Servidor:</strong> <?php echo htmlspecialchars(php_uname()); ?></li>
        <li><strong>Idioma del Navegador (Cliente):</strong> <?php echo htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE']); ?></li>
    </ul>


</body>

</html>