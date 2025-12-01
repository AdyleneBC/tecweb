<?php

namespace MYAPI\Delete;

use MYAPI\DataBase;

class Delete extends DataBase
{
    // Delete(db: string)
    public function __construct($db)
    {
        parent::__construct('root', 'adylene', $db);
    }

    // delete(string): void
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
}
