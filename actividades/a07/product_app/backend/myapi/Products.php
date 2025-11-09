<?php
namespace TECWEB\MYAPI;

use mysqli;
use TECWEB\MYAPI\DataBase as DataBase;
require_once __DIR__ . '/DataBase.php';

class Products extends DataBase{
    private $data = NULL;

    //CONSTRUCTOR******************************************************
    public function __construct($user='root', $pass='adylene', $db){
        $this->data = array();
        parent::__construct($user, $pass, $db);
    }
    
    //Método list********************************
    public function list(){
        //SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
        $this->data = array();
        //SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
        if ($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")){
            //SE OBTIENEN LOS RESULTADOS
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if(!is_null($rows)){
                //SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                foreach($rows as $num => $row){
                    foreach($row as $key => $value){
                        $this->data[$num][$key] = $value;
                    }
                }
            }
            $result->free();
        }else{
            die('Query Error: '.mysqli_error($this->conexion));
        }
        $this->conexion->close();
    }

    //METODO getData********************************************************
    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
        
    }
  
}



?>