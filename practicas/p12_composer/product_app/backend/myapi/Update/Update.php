<?php

namespace TECWEB\MYAPI\Update;

use TECWEB\MYAPI\DataBase;

class Update extends DataBase
{
    // Update(db: string)
    public function __construct($db)
    {
        parent::__construct('root', 'adylene', $db);
    }

    // edit(Object): void
    public function edit($obj)
    {
        $id       = intval($obj['id']       ?? 0);
        $nombre   = $this->conexion->real_escape_string($obj['nombre']   ?? '');
        $marca    = $this->conexion->real_escape_string($obj['marca']    ?? '');
        $modelo   = $this->conexion->real_escape_string($obj['modelo']   ?? '');
        $precio   = floatval($obj['precio']   ?? 0);
        $unidades = intval($obj['unidades'] ?? 0);
        $detalles = $this->conexion->real_escape_string($obj['detalles'] ?? '');
        $imagen   = $this->conexion->real_escape_string($obj['imagen']   ?? '');

        $sql = "UPDATE productos SET
                    nombre='$nombre',
                    marca='$marca',
                    modelo='$modelo',
                    precio=$precio,
                    unidades=$unidades,
                    detalles='$detalles',
                    imagen='$imagen'
                WHERE id=$id";

        if ($this->conexion->query($sql)) {
            $this->data = array('status' => 'success', 'message' => 'Producto modificado');
        } else {
            $this->data = array('status' => 'error', 'message' => $this->conexion->error);
        }

        $this->conexion->close();
    }
}
