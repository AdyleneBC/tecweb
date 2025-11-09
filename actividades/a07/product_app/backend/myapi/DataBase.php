<?php

namespace TECWEB\MYAPI;

abstract class DataBase
{
    protected $conexion;
    public function __construct($db, $user, $pass)
    {
        $this->conexion = @mysqli_connect('localhost', $user, $pass, $db);

        //Si la conexión falló $conexión contendrá false 
        if (!$this->conexion) {
            die('¡Base de datos NO conectada!');
        }
    }
}
