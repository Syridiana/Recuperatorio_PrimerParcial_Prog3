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
