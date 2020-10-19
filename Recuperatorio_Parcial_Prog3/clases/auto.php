<?php
require_once 'FileManager.php';

class Auto
{
    public $_marca;
    public $_modelo;
    public $_patente;
    public $_precio;

    public static $_pathTxt = './archivos/vehiculos.txt';
    public static $_pathJson = './archivos/vehiculos.json';
    public static $_pathSerialize = './archivos/vehiculosSerialize.txt';

    public function __construct($marca, $modelo, $patente, $precio)
    {
        if (!is_null($marca) && is_string($marca)) {
            $this->_marca = $marca;
        }
        if (!is_null($modelo) && is_string($modelo)) {
            $this->_modelo = $modelo;
        }
        if (!is_null($patente) && is_string($patente)) {
            $patente = strtolower($patente);
            $this->_patente = $patente;
        }
        if (!is_null($precio)) {
            $this->_precio = $precio;
        }
       
    }

    public static function autoEsValido($auto)
    {
     
        $rta = [true];
        if (
            !($auto->_marca == null) && !($auto->_modelo == null)
            && !($auto->_patente == null) && !($auto->_precio == null)
        ) {
            $autos = Auto::leerTxt(Auto::$_pathTxt);

            foreach ($autos as $value) {
                
                if ($value->_patente == $auto->_patente) {

                    $rta = [false, "Este auto ya está ingresado... No se guardó"];
                }
            }
        } else {
            $rta = [false, "No se permiten campos vacíos"];
        }
        return $rta;
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

        FileManager::guardarJson($objeto, Auto::$_pathJson);
    }

      
    public static function leerJson() {

        $archivoArray = (array) FileManager::leerJson(Auto::$_pathJson);

        $listaAutos = [];

       foreach($archivoArray as $datos)
        {
            $nuevoAuto = new Auto($datos->_patente, $datos->_tipoEstadia, $datos->_emailUsuario, $datos->_fechaIngreso);
            array_push($listaAutos, $nuevoAuto);
        }

        return $listaAutos;
     }


     public function guardarTxt() {
        FileManager::guardarTxt(Auto::$_pathTxt, $this);
    }

  
    public static function leerTxt() {

        $archivoAutos = FileManager::BringArray(Auto::$_pathTxt);

        $listaAutos=array();

        foreach($archivoAutos as $datos)
        {  

            if(count($datos) == 4)
            {
                $auto = new Auto($datos[0], $datos[1], $datos[2], $datos[3]);
                array_push($listaAutos, $auto);


            }

        }


        return $listaAutos;
    }



    public static function getAuto($patente)
    {
        $autos = Auto::leerTxt();

        foreach ($autos as $auto) {
            if ($auto->_patente == $patente) {
                return $auto;
            }
        }
        return false;
    }

    public static function getAutosPorTipo()
    {
        $autos = Auto::leerJson();

        $autosOrdenados = array();

        foreach ($autos as $auto) {
            if ($auto->_tipoEstadia == "hora") {
                array_push($autosOrdenados, $auto);
            }
        }

        foreach ($autos as $auto) {
            if ($auto->_tipoEstadia == "estadia") {
                array_push($autosOrdenados, $auto);
            }
        }

        foreach ($autos as $auto) {
            if ($auto->_tipoEstadia == "mensual") {
                array_push($autosOrdenados, $auto);
            }
        }

        return $autosOrdenados;
    }

    public static function importePorTipo($valor, $tipo)
    {
        $autos = Auto::getAutosPorTipo();
        $total = 0;
        foreach ($autos as $auto) {
            if ($auto->_tipoEstadia == $tipo) {
                $total = $valor +  $total;
            }
        }
        return $total;
    }


    public function __toString(){
        return $this->_marca.'*'.$this->_modelo.'*'.$this->_patente.'*'.$this->_precio;
     }
}
