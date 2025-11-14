<?php

namespace MYAPI\Create;


use MYAPI\DataBase;

class Create extends DataBase
{
    // Constructor: Create(db: string)  (UML)
    public function __construct($db)
    {
        // usuario y contraseña fijos (como ya los usabas)
        parent::__construct('root', 'adylene', $db);
    }

    // add(Object): void
    public function add($obj)
    {
        $nombre   = $this->conexion->real_escape_string($obj['nombre']   ?? '');
        $marca    = $this->conexion->real_escape_string($obj['marca']    ?? '');
        $modelo   = $this->conexion->real_escape_string($obj['modelo']   ?? '');
        $precio   = floatval($obj['precio']   ?? 0);
        $unidades = intval($obj['unidades']   ?? 0);
        $detalles = $this->conexion->real_escape_string($obj['detalles'] ?? '');
        $imagen   = $this->conexion->real_escape_string($obj['imagen']   ?? '');

        $sql = "INSERT INTO productos (nombre, marca, modelo, precio, unidades, detalles, imagen, eliminado)
                VALUES ('$nombre', '$marca', '$modelo', $precio, $unidades, '$detalles', '$imagen', 0)";

        if ($this->conexion->query($sql)) {
            $this->data = array('status' => 'success', 'message' => 'Producto agregado');
        } else {
            $this->data = array('status' => 'error', 'message' => $this->conexion->error);
        }

        $this->conexion->close();
    }
}
