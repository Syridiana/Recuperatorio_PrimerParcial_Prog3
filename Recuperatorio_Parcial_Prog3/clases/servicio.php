<?php
require_once 'FileManager.php';

class Servicio
{
    public $_id;
    public $_tipo;
    public $_precio;
    public $_demora;

    public static $_pathTxt = './archivos/tiposServicio.txt';
    public static $_pathJson = './archivos/tiposServicio.json';
    public static $_pathSerialize = './archivos/tiposServicio.txt';

    public function __construct($id, $tipo, $precio, $demora)
    {
        if (!is_null($id)) {
            $this->_id = $id;
        }
        if (!is_null($tipo)) {
            $this->_tipo = $tipo;
        }
        if (!is_null($precio)) {
            $this->_precio = $precio;
        }
        if (!is_null($demora)) {
            $this->_demora = $demora;
        }
       
    }


    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }



    public static function guardarJson($objeto) {

        FileManager::guardarJson($objeto, Servicio::$_pathJson);
    }

      
    public static function leerJson() {

        $archivoArray = (array) FileManager::leerJson(Servicio::$_pathJson);

        $listaServicios = [];

       foreach($archivoArray as $datos)
        {
            $nuevoServicio = new Servicio($datos->_id, $datos->_tipo, $datos->_precio, $datos->_demora);
            array_push($listaServicios, $nuevoServicio);
        }

        return $listaServicios;
     }



public static function getServicio($id)
{
    $servicios = Servicio::leerJson();

    foreach ($servicios as $value) {
        if ($value->_id == $id) {
            return $value;
        }
    }
    return null;
}

    public function __toString(){
        return  "ID: $this->_id | Tipo: $this->_tipo | Precio: $$this->_precio | Demora: $this->_demora";
     }
}
