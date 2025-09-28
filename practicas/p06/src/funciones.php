<?php

/*EJERCICIO 1 */
function funcion1()
{

    if (isset($_GET['numero'])) {
        $num = $_GET['numero'];
        if ($num % 5 == 0 && $num % 7 == 0) {
            echo '<h3>R= El número ' . $num . ' SÍ es múltiplo de 5 y 7.</h3>';
        } else {
            echo '<h3>R= El número ' . $num . ' NO es múltiplo de 5 y 7.</h3>';
        }
    }
}

/*Funcion para generar una matriz de M*3 y almacenar todas la iteraciones hasta llegar a que un numero sea impar, par, impar */
/*Vamos a usar un do-while */

/*EJERCICIO 2 */
function ejercicio2()
{
    $matriz = [];   // Guardará los números en formato Mx3
    $iteraciones = 0;
    $secuencia = false;

    while (!$secuencia) {
        $fila = [];
        // Generamos los 3 números aleatorios
        for ($i = 0; $i < 3; $i++) {
            $fila[] = rand(1, 999);
        }

        // Guardamos la fila en la matriz
        $matriz[] = $fila;
        $iteraciones++;

        // Verificamos si la ultima fila es impar, par, impar
        if ($fila[0] % 2 != 0 && $fila[1] % 2 == 0 && $fila[2] % 2 != 0) {
            $secuencia = true;
        }
    }

    // Imprimimos la matriz con comas
    foreach ($matriz as $fila) {
        echo implode(", ", $fila) . "<br>";
    }


    $total = $iteraciones * 3;
    echo "<br>$total números obtenidos en $iteraciones iteraciones\n";
}

/*EJERCICIO 3 */
function ejercicio3_while()
{


    if (isset($_GET['num'])) {
        $divisor = (int) $_GET['num'];

        if ($divisor <= 0) {
            echo "El número debe ser mayor a 0 (cero)";
            exit;
        }

        $numero = rand(1, 100); 

        // Ciclo while
        while ($numero % $divisor != 0) {
            echo "$numero no es múltiplo de $divisor<br>";
            $numero = rand(1, 100);
        }

        echo "<br><b>Encontrado:</b> $numero es múltiplo de $divisor";
        echo "<br><br>";

    } else {
        echo "Comprueba tu entrada";
    }
}

function ejercicio3_do_while()
{
    if (isset($_GET['num'])) {
        $divisor = (int) $_GET['num'];

        if ($divisor <= 0) {
            echo "El número debe ser mayor a 0 (cero)";
            exit;
        }

        do {
            $numero = rand(1, 100);
            
            echo "$numero generado<br>";
        } while ($numero % $divisor != 0);

        echo "<br><b>Encontrado:</b> $numero es múltiplo de $divisor";
    } else {
        echo "Comprueba el número que ingresas";
    }
}
