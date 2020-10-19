<?php
require_once 'FileManager.php';

class Turno
{
    public $_fecha;
    public $_patente;
    public $_marca;
    public $_modelo;
    public $_precio;
    public $_tipoDeServicio;

    public static $_pathTxt = './archivos/turnos.txt';
    public static $_pathJson = './archivos/turnos.json';
    public static $_pathSerialize = './archivos/turnos.txt';

    public function __construct($fecha, $patente, $marca, $modelo, $precio, $tipoDeServicio)
    {
        if (!is_null($fecha)) {
            $this->_fecha = $fecha;
        }
        if (!is_null($patente)) {
            $this->_patente = $patente;
        }
        if (!is_null($marca)) {
            $this->_marca = $marca;
        }
        if (!is_null($modelo)) {
            $this->_modelo = $modelo;
        }
        if (!is_null($precio)) {
            $this->_precio = $precio;
        }
        if (!is_null($tipoDeServicio)) {
            $this->_tipoDeServicio = $tipoDeServicio;
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

        FileManager::guardarJson($objeto, Turno::$_pathJson);
    }

      
    public static function leerJson() {

        $archivoArray = (array) FileManager::leerJson(Turno::$_pathJson);

        $listaTurnos = [];

       foreach($archivoArray as $datos)
        {
            $nuevoTurno = new Turno($datos->_fecha, $datos->_patente, $datos->_marca, $datos->_modelo, $datos->_precio, $datos->_tipoDeServicio);
            array_push($listaTurnos, $nuevoTurno);
        }

        return $listaTurnos;
     }



    public function __toString(){
        return $this->_id.'*'.$this->_tipo.'*'.$this->_servicio.'*'.$this->_demora;
     }
}
