<?php

namespace MYAPI;

abstract class DataBase
{
    // atributo protegido de conexión
    protected $conexion;

    // arreglo donde guardaremos los datos que regresan las consultas
    protected $data;

    // Constructor: DataBase(user: string, pass: string, db: string)
    public function __construct($user, $pass, $db)
    {
        $this->data = array();

        $this->conexion = @mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );

        // Si la conexión falla, $conexion será false
        if (!$this->conexion) {
            die('¡Base de datos NO conectada!');
        }
    }

    // getData(): string
    public function getData()
    {
        // Regresa los datos como JSON
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
