<?php
require_once 'FileManager.php';

use \Firebase\JWT\JWT;

class Usuario 
{
    public $_email;
    public $_password;
    public $_tipo;

    public static $_pathTxt = './archivos/users.txt';
    public static $_pathJson = './archivos/users.json';
    public static $_pathSerialize = './archivos/usersSerialize.txt';


    public function __construct($email, $password, $tipo)
    {
        if (!is_null($email) && is_string($email)) {
            $this->_email = $email;
        }
        if (!is_null($password) && is_string($password)) {
            $this->_password = $password;
        }
        if (!is_null($tipo) && is_string($tipo)) {
            $this->_tipo = $tipo;
        }
    }

    public static function existeUsuario($user)
    {
        if (
            isset($user->_email) && isset($user->_password)
            && isset($user->_tipo) && !empty($user->_password)
            && !empty($user->_email) && !empty($user->_tipo)
        ) {
            $usuarios = Usuario::leerJson(Usuario::$_pathJson);
            foreach ($usuarios as $value) {

                if ($value->_email == $user->_email) {
                    return [false, "Email repetido... No se guardó"];
                }
            }
        } else {
            return [false, "No se permiten campos vacíos"];
        }

        return [true];
    }

    public static function login($email, $password)
    {

        $logueado = false;
        $usuarios = Usuario::leerJson(Usuario::$_pathJson);


        foreach ($usuarios as $user) {
            if ($user->_email == $email && $user->_password == PassManager::Create($password)) {
                $logueado = true;
                break;
            }
        }

        if($logueado)
        {
            return Token::getToken($user->_email, $user->_tipo);
        }

        return "";
    }

    public static function esUsuarioValido($token)
    {
        try {
            JWT::decode($token, "primerparcial", array('HS256'));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function esAdmin($token)
    {
        $admin = Token::tipoUsuarioSegunToken($token);
        if ($admin == "admin") {
            return true;
        } else {
            return false;
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

    public function __toString()
    {
        return $this->_email . '*' . $this->_tipo . PHP_EOL;
    }

    public static function guardarJson($objeto) {

        FileManager::guardarJson($objeto, Usuario::$_pathJson);
    }

  
    public static function leerJson(string $path) {

       $archivoArray = (array) FileManager::leerJson($path);

      // var_dump($archivoArray);

       $listaUsuarios = [];

      foreach($archivoArray as $datos)
       {

           $nuevoUsuario = new Usuario($datos->_email,  $datos->_password, $datos->_tipo);
           array_push($listaUsuarios, $nuevoUsuario);
       }

       return $listaUsuarios;
    }
}
