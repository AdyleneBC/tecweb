<?php

namespace TECWEB\MYAPI\Read;

use TECWEB\MYAPI\DataBase;

class Read extends DataBase
{
    // Read(db: string)
    public function __construct($db)
    {
        parent::__construct('root', 'adylene', $db);
    }

    // list(): void
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

    // search(string): void
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

    // single(string): void
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
}
