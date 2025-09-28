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

/*EJERCICIO 3 usando while*/
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

/*Ejercicio3 usando do while */
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

/*EJERCICIO 4 */

function ejercicio4()
{
    $arreglo = [];
    for ($i = 97; $i <= 122; $i++) {
        $arreglo[$i] = chr($i);
    }

    //Tabla en XHTML con echo
    echo "<table border='1' cellpadding='20'>";
    echo "<tr><th>Índice</th><th>Valor</th></tr>";

    foreach ($arreglo as $key => $value) {
        echo "<tr>";
        echo "<td>$key</td>";
        echo "<td>$value</td>";
        echo "</tr>";
    }

    echo "</table>";
}

/*EJERCICIO 5 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];

    if ($sexo == "femenino" && $edad >= 18 && $edad <= 35) {
        echo "<script>window.alert('Bienvenida, usted está en el rango de edad permitido.');</script>";
    } else {
        echo "<script>window.alert('Verifique los datos ingresados');</script>";
    }
}


/*EJERCICIO 6 */
function matriculas()
{
    return [
        "ABC1234" => [
            "Auto" => [
                "Marca" => "Mazda",
                "Modelo" => 2022,
                "Tipo" => "Sedan"
            ],
            "Propietario" => [
                "Nombre" => "Ricardo Perez",
                "Ciudad" => "Oaxaca",
                "Dirección" => "Av. Hidalgo"
            ]
        ],
        "XYZ5664" => [
            "Auto" => [
                "Marca" => "Honda",
                "Modelo" => 2020,
                "Tipo" => "Hatchback"
            ],
            "Propietario" => [
                "Nombre" => "Silvia Vargas",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Avenida Independencia 222"
            ]
        ],
        "LMN7012" => [
            "Auto" => [
                "Marca" => "Ford",
                "Modelo" => 2019,
                "Tipo" => "Camioneta"
            ],
            "Propietario" => [
                "Nombre" => "Roberto Castillo",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle de los Libres 333"
            ]
        ],
        "DEF3456" => [
            "Auto" => [
                "Marca" => "Chevrolet",
                "Modelo" => 2021,
                "Tipo" => "Sedan"
            ],
            "Propietario" => [
                "Nombre" => "Fernanda López",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle García Vigil 444"
            ]
        ],
        "GHI6789" => [
            "Auto" => [
                "Marca" => "Nissan",
                "Modelo" => 2018,
                "Tipo" => "Hatchback"
            ],
            "Propietario" => [
                "Nombre" => "Emilio Ramos",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Manuel Doblado 555"
            ]
        ],
        "JKL1122" => [
            "Auto" => [
                "Marca" => "Mazda",
                "Modelo" => 2022,
                "Tipo" => "Camioneta"
            ],
            "Propietario" => [
                "Nombre" => "Carla Méndez",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle de la Constitución 666"
            ]
        ],
        "MNO3344" => [
            "Auto" => [
                "Marca" => "Volkswagen",
                "Modelo" => 2017,
                "Tipo" => "Sedan"
            ],
            "Propietario" => [
                "Nombre" => "Javier Ortega",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle 20 de Noviembre 777"
            ]
        ],
        "PQR5566" => [
            "Auto" => [
                "Marca" => "Hyundai",
                "Modelo" => 2020,
                "Tipo" => "Hatchback"
            ],
            "Propietario" => [
                "Nombre" => "Adriana Guzmán",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Las Casas 888"
            ]
        ],
        "STU7788" => [
            "Auto" => [
                "Marca" => "Kia",
                "Modelo" => 2021,
                "Tipo" => "Camioneta"
            ],
            "Propietario" => [
                "Nombre" => "Gerardo Sánchez",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Mina 999"
            ]
        ],
        "VWX9900" => [
            "Auto" => [
                "Marca" => "Jeep",
                "Modelo" => 2016,
                "Tipo" => "Camioneta"
            ],
            "Propietario" => [
                "Nombre" => "Margarita Flores",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Porfirio Díaz 1010"
            ]
        ],
        "YZA2233" => [
            "Auto" => [
                "Marca" => "Mercedes-Benz",
                "Modelo" => 2023,
                "Tipo" => "Sedan"
            ],
            "Propietario" => [
                "Nombre" => "Oscar Navarro",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Reforma 1111"
            ]
        ],
        "BCD4455" => [
            "Auto" => [
                "Marca" => "BMW",
                "Modelo" => 2022,
                "Tipo" => "Hatchback"
            ],
            "Propietario" => [
                "Nombre" => "Daniela Pineda",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Crespo 1212"
            ]
        ],
        "EFG6677" => [
            "Auto" => [
                "Marca" => "Audi",
                "Modelo" => 2020,
                "Tipo" => "Sedan"
            ],
            "Propietario" => [
                "Nombre" => "Raúl Velázquez",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Morelos 1313"
            ]
        ],
        "HIJ8899" => [
            "Auto" => [
                "Marca" => "Tesla",
                "Modelo" => 2021,
                "Tipo" => "Sedan"
            ],
            "Propietario" => [
                "Nombre" => "Andrea Córdova",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Vicente Guerrero 1414"
            ]
        ],
        "KLM1123" => [
            "Auto" => [
                "Marca" => "Subaru",
                "Modelo" => 2019,
                "Tipo" => "Camioneta"
            ],
            "Propietario" => [
                "Nombre" => "Luis Moreno",
                "Ciudad" => "Oaxaca de Juárez",
                "Dirección" => "Calle Hidalgo 1515"
            ]
        ]
    ];
}
