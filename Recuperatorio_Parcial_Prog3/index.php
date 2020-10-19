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
