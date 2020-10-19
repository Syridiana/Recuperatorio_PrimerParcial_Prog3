<?php

require __DIR__ . '\vendor\autoload.php';
include_once __DIR__ . '.\vendor\Firebase\php-jwt\src\JWK.php';

require_once './clases/auto.php';
require_once './clases/usuario.php';
require_once './clases/PassManager.php';
require_once './clases/Token.php';
require_once './clases/servicio.php';
require_once './clases/turno.php';




$method = $_SERVER['REQUEST_METHOD'];
$path_info = $_SERVER['PATH_INFO'];

function getToken()
{
    $headers = getallheaders();
    if (isset($headers['token'])) {
        return $headers['token'];
    } else {
        return false;
    }
}

$token = getToken();

switch ($method) {
    case 'GET':


        switch ($path_info) {
            case '/turno':

                if (Usuario::esUsuarioValido($token)) {
                    if (isset($_GET['patente']) && isset($_GET['fecha']) && isset($_GET['tipoServicio'])) {


                        $patente = $_GET['patente'];
                        $fecha = $_GET['fecha'];
                        $tipoServicio = $_GET['tipoServicio'];


                        $auto = Auto::getAuto($patente);



                        if (!is_null($auto)) {

                            $nuevoTurno = new Turno($fecha, $patente, $auto->_marca, $auto->_modelo, $auto->_precio, $tipoServicio);

                            Turno::guardarJson($nuevoTurno);

                            echo "Turno guardado";
                        } else {
                            echo "No existe $patente";
                        }
                    }
                } else {
                    echo "No autorizado...";
                }
                break;
                /*
                            case '/importe/hora':
                            


                                    if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_final'])) {
                                        if (Usuario::esUsuarioValido($token) && Usuario::esAdmin($token))
                                        {
                                        
                                                $fechaInicio = $_GET['fecha_inicio'];
                                                $fechaFinal = $_GET['fecha_final'];
                                                $monto = 0;
                    
                                                $listadoEgresos = Egresos::leerJson();
                                                
                                                $exploded_inicio = explode("/", $fechaInicio);
                                                $exploded_final = explode("/", $fechaFinal);

                                                $di=strtotime($exploded_inicio[1] . "/" . $exploded_inicio[0] . "/" . $exploded_inicio[2]);
                                                $exploded_inicio = date ( "d-m-Y H", $di);

                                                $df=strtotime($exploded_final[1] . "/" . $exploded_final[0] . "/" . $exploded_final[2]);
                                                $exploded_final = date ( "d-m-Y H", $df);
                    
                                                foreach($listadoEgresos as $egreso)
                                                {
                                                    if($egreso->_fechaEgreso >= $exploded_inicio && $egreso->_fechaEgreso <= $exploded_final){
                                                        $monto += $egreso->_monto;
                                                    }
                                                }
                    
                                            }else
                                            {
                        
                                                echo "Usuario no valido.";
                                            }
                    
                                            echo "Total cobrado entre ambas fechas: $" . $monto;
                
                                    } else
                                    {
                                        echo "$".Auto::importePorTipo(Precios::importePorTipo("hora"), "hora");
                                    }
                                

                            break;
                            case '/importe/estadia':
                                echo "$".Auto::importePorTipo(Precios::importePorTipo("estadia"), "estadia");
                            break;
                            case '/importe/mensual':
                                echo "$".Auto::importePorTipo(Precios::importePorTipo("mensual"), "mensual");
                            break;
                                */

            default:
                if (preg_match("/patente/", $path_info)) {

                    if (Usuario::esUsuarioValido($token)) {
                        $path_explode = explode("/", $path_info);

                        $plate = $path_explode[2];
                        $plate = strtolower($plate);

                        $auto = Auto::getAuto($plate);

                        if ($auto && !($auto == null)) {
                            echo "Marca: $auto->_marca | Modelo: $auto->_modelo | Patente: $auto->_patente | Precio: $$auto->_precio";
                        } else {
                            echo "No existe $plate";
                        }

                        /*    $dateNow = date("d-m-Y H". ":00:00");
                        $dateCar = $auto->_fechaIngreso; 



                        $date1 = strtotime($dateNow);  
                        $date2 = strtotime($dateCar);  


                        $diff = abs($date2 - $date1);  
                        
                        

                        $years = floor($diff / (365*60*60*24));  
                        
                        
                        $months = floor(($diff - $years * 365*60*60*24) 
                                                    / (30*60*60*24));  
                        
                        $days = floor(($diff - $years * 365*60*60*24 -  
                                    $months*30*60*60*24)/ (60*60*24)); 
                        

                        $hours = floor(($diff - $years * 365*60*60*24  
                            - $months*30*60*60*24 - $days*60*60*24) 
                                                        / (60*60));  

                        
                        switch($auto->_tipoEstadia){
                            case "hora":
                            echo "Datos del auto: " .$auto . "</br>";
                            $precios = Precios::leerJson();
                            echo "Importe a abonar: $" . ($hours * $precios->_hora);
                            $nuevoEgreso = new Egresos($auto->_patente, $auto->_tipoEstadia, $auto->_emailUsuario, $auto->_fechaIngreso, $dateNow, $hours * $precios->_hora);
                            Egresos::guardarJson($nuevoEgreso);
                            break;

                            case "estadia":
                                echo "Datos del auto: " .$auto . "</br>";
                                $precios = Precios::leerJson();
                                echo "Importe a abonar: $" . ($days * $precios->_estadia);
                                $nuevoEgreso = new Egresos($auto->_patente, $auto->_tipoEstadia, $auto->_emailUsuario, $auto->_fechaIngreso, $dateNow, $days * $precios->_estadia);
                                Egresos::guardarJson($nuevoEgreso);
                            break;

                            case "mensual":
                                echo "Datos del auto: " .$auto . "</br>";
                                $precios = Precios::leerJson();
                                echo "Importe a abonar: $" . ($months * $precios->_mensual);
                                $nuevoEgreso = new Egresos($auto->_patente, $auto->_tipoEstadia, $auto->_emailUsuario, $auto->_fechaIngreso, $dateNow, $months * $precios->_mensual);
                                Egresos::guardarJson($nuevoEgreso);
                            break;

                        }*/
                    } else {
                        echo "No autorizado...";
                    }
                }

                break;
        }

        break;

    case 'POST':
        switch ($path_info) {
            case '/registro':

                if (isset($_POST['email']) && isset($_POST['password'])) {
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $clave = PassManager::Create($password);
                    $usuario = new Usuario($_POST['email'],  $clave, $_POST['tipo']);


                    $usuario = new Usuario($_POST['email'],  $clave, $_POST['tipo']);

                    $existe = Usuario::existeUsuario($usuario);

                    if ($existe[0]) {
                        Usuario::guardarJson($usuario);
                    } else {
                        echo $existe[1];
                    }
                } else {
                    echo "Debe completar todos los campos.";
                }
                break;


            case '/login':


                $login = Usuario::login($_POST['email'],  $_POST['password']);

                if ($login != false) {
                    echo $login;
                } else {
                    echo "Usuario o clave Incorrectos";
                }
                break;

                /*  case '/precio':

                if (Usuario::esUsuarioValido($token) && Usuario::esAdmin($token)) {


                    $precios = new Precios($_POST['precio_hora'], $_POST['precio_estadia'], $_POST['precio_mensual']);
                    $rta = Precios::estaSeteado($precios);
                    if ($rta[0]) {
                        Precios::sobreEscribirJson($precios);
                    } else {
                        echo $rta[1];
                    }
                } else {
                    echo "No es administrador autorizado...";
                }
                break;*/
            case '/vehiculo':

                if (Usuario::esUsuarioValido($token)) {

                    if (isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['patente']) && isset($_POST['precio'])) {




                        $marca = $_POST['marca'];
                        $modelo = $_POST['modelo'];
                        $patente = $_POST['patente'];
                        $precio = $_POST['precio'];


                        $nuevoAuto = new Auto($marca, $modelo,  $patente, $precio);

                        $esValido = Auto::autoEsValido($nuevoAuto);

                        if ($esValido[0]) {
                            $nuevoAuto->guardarTxt();

                            echo "Datos guardados";
                        } else {
                            echo $esValido[1];
                        }
                    }
                } else {
                    echo "No autorizado...";
                }


                break;


            case '/servicio':

                if (Usuario::esUsuarioValido($token)) {
                    if (isset($_POST['id']) && isset($_POST['tipo']) && isset($_POST['precio']) && isset($_POST['demora'])) {




                        $tipo = $_POST['tipo'];
                        $id = $_POST['id'];
                        $demora = $_POST['demora'];
                        $precio = $_POST['precio'];


                        $nuevoServicio = new Servicio($id, $tipo,  $precio, $demora);

                        $existeServicio = Servicio::getServicio($id);

                        if (!is_null($existeServicio)) {
                            echo "Error. Ya existe un servicio con ese ID.";
                        } else {
                            Servicio::guardarJson($nuevoServicio);
                            echo "Servicio guardado con exito";
                        }
                    }
                } else {
                    echo "No autorizado...";
                }


                break;
            case '/stats':

                if (Usuario::esUsuarioValido($token) && Usuario::esAdmin($token)) {
                    if (isset($_POST['tipoServicio'])) {

                        $tipoServicio = $_POST['tipoServicio'];

                        $servicios = Servicio::leerJson();

                        $existe = false;

                        foreach ($servicios as $value) {
                            if ($value->_tipo == $tipoServicio) {
                                echo $value;
                                $existe = true;
                            }
                        }

                        if(!$existe)
                        {
                            echo "No se encontró ningun servicio con la descripcion \"$tipoServicio\"";
                        }


                    } else if (isset($_POST['idTipoServicio'])) {

                        $idTipoServicio = $_POST['idTipoServicio'];

                       $servicio = Servicio::getServicio($idTipoServicio);

                        if(!is_null($servicio))
                        {
                            echo $servicio;
                        } else
                        {
                            echo "No se encontró ningun servicio con el ID $idTipoServicio";
                        }

                            
                        }



                     else {
                        // $tipoServicio = $_POST['tipoServicio'];

                        $servicios = Servicio::leerJson();
                        foreach ($servicios as $value) {

                            echo "$value <br>";
                        }
                    }
                } else {
                    echo "No autorizado.";
                }



                break;
        }

        break;
}
