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
/*
    public static function autoEsValido($auto)
    {
        //RTA ES UN ARRAY QUE TIENE UN CAMPO QUE ES TRUE POR DEFAULT
        $rta = [true];
        if (
            !($auto->_marca == null) && !($auto->_modelo == null)
            && !($auto->_patente == null) && !($auto->_precio == null)
        ) {
            $autos = Auto::leerTxt(Auto::$_pathTxt);
         //   var_dump($autos);
            foreach ($autos as $value) {
                
              //  var_dump($value);
                if ($value->_patente == $auto->_patente) {
                    //SI EL OBJETO SE REPITE ASIGNO MENSAJE AL SEGUNDO INDICE
                    $rta = [false, "Este auto ya está ingresado... No se guardó"];
                }
            }
        } else {
            //SI ALGUN CAMPO ESTÁ VACÍO ASIGNO MENSAJE AL SEGUNDO INDICE
            $rta = [false, "No se permiten campos vacíos"];
        }
        return $rta;
    }
*/

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

/*
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
*/

    public function __toString(){
        return $this->_id.'*'.$this->_tipo.'*'.$this->_servicio.'*'.$this->_demora;
     }
}
