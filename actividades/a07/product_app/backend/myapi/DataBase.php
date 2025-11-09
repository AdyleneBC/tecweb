<?php

namespace TECWEB\MYAPI;

abstract class DataBase
{
    /** @var \mysqli|null */
    protected $conexion;

    /**
     * Constructor siguiendo el modelo UML que nos proporiono el pdf
     *
     * @param string $db   Nombre de la base de datos (obligatorio)
     * @param string $user Usuario de la BD (obligatorio)
     * @param string $pass Password de la BD (obligatorio)
     */
    public function __construct(string $db, string $user, string $pass)
    {
        // Conexión orientada a objetos (tipo mysqli)
        $this->conexion = new \mysqli('localhost', $user, $pass, $db);

        if ($this->conexion->connect_errno) {
            die('Base de datos NO conectada: ' . $this->conexion->connect_error);
        }

        // Forzar UTF-8
        $this->conexion->set_charset('utf8');
    }

    // Método protegido para cerrar la conexión (opcional pero recomendable)
    /*protected function closeConnection(): void
    {
        if ($this->conexion instanceof \mysqli) {
            $this->conexion->close();
            $this->conexion = null;
        }
    }*/
}
