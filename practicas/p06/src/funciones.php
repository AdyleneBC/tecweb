<?php

    function funcion1(){

        if(isset($_GET['numero']))
        {
            $num = $_GET['numero'];
            if ($num%5==0 && $num%7==0)
            {
                echo '<h3>R= El número '.$num.' SÍ es múltiplo de 5 y 7.</h3>';
            }
            else
            {
                echo '<h3>R= El número '.$num.' NO es múltiplo de 5 y 7.</h3>';
            }
        }
    }

    /*Funcion para generar una matriz de M*3 y almacenar todas la iteraciones hats llegar a que un numero sea impar, par, impar */
    /*Vamos a usar un do-while */

    function funcion2 (){
        do{
            $val1 = rand();
            $val2 = rand();
            $val3 = rand();

            echo $val2;

        }while($val2%2==0);
        

        
        
    }

        
    ?>