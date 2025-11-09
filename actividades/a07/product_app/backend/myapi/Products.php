<?php

namespace TECWEB\MYAPI;

use TECWEB\MYAPI\DataBase as DataBase;

require_once __DIR__ . '/DataBase.php';

class Products extends DataBase
{
    private $data = NULL;
    // CONSTRUCTOR************************
    public function __construct($db, $user = 'root', $pass = 'adylene')
    {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    // add******************************************
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

    // delete*******************************************
    public function delete($id)
    {
        $id  = intval($id);
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";

        if ($this->conexion->query($sql)) {
            $this->data = array('status' => 'success', 'message' => 'Producto eliminado');
        } else {
            $this->data = array('status' => 'error', 'message' => $this->conexion->error);
        }
        $this->conexion->close();
    }

    // edit******************************************
    public function edit($obj)
    {
        $id       = intval($obj['id'] ?? 0);
        $nombre   = $this->conexion->real_escape_string($obj['nombre']   ?? '');
        $marca    = $this->conexion->real_escape_string($obj['marca']    ?? '');
        $modelo   = $this->conexion->real_escape_string($obj['modelo']   ?? '');
        $precio   = floatval($obj['precio']   ?? 0);
        $unidades = intval($obj['unidades']   ?? 0);
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

    // list*****************************
    public function list()
    {
        $this->data = array();
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . $this->conexion->error);
        }
        $this->conexion->close();
    }

    // search*****************************************
    public function search($string)
    {
        $q = $this->conexion->real_escape_string($string);
        $condId = '';
        if (is_numeric($string)) {
            $id = intval($string);
            $condId = " OR id = $id ";
        }

        $sql = "SELECT * FROM productos
                WHERE eliminado = 0 AND (
                    nombre  LIKE '%$q%' OR
                    marca   LIKE '%$q%' OR
                    modelo  LIKE '%$q%' OR
                    detalles LIKE '%$q%'
                    $condId
                )";

        $this->data = array();
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . $this->conexion->error);
        }
        $this->conexion->close();
    }

    // single*******************************************************
    public function single($string)
    {
        $id  = intval($string);
        $sql = "SELECT * FROM productos WHERE id = $id LIMIT 1";

        $this->data = array();
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . $this->conexion->error);
        }
        $this->conexion->close();
    }

    // singleByName---------------------------------------
    public function singleByName($string)
    {
        $name = $this->conexion->real_escape_string($string);
        $sql  = "SELECT * FROM productos WHERE nombre = '$name' LIMIT 1";

        $this->data = array();
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: ' . $this->conexion->error);
        }
        $this->conexion->close();
    }

    // getData**************************************************
    public function getData()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
