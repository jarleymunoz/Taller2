<?php

require_once '../vendor/autoload.php'; //libreria de composer
require_once '../db/db.php'; //librerias de base datos
require_once '../libs/tools.php'; //libreria de funciones de validaciones

use Firebase\JWT\JWT; //libreria de jwt

$key = 'my_secret_key';
$time = time();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = Limpieza($_POST['usuario']);
    $clave = Limpieza($_POST['clave']);
    if (validarUsuario($usuario) && validarClave($clave)) {
        if (!isset($usuario) || $usuario == '') {
            echo 'Ingrese usuario';
            http_response_code(401);
            exit();
        }
        if (validaClave($usuario, $clave)) {
            $data = array(
                'iat' => $time, //Tiempo de inicio del token
                'exp' => $time + (60 * 60), //Tiempo que expira el token   
                'data' => ['usuario' => $usuario]
            );
            $jwt = JWT::encode($data, $key, 'HS256');
            echo $jwt;
            header("HTTP/1.1 200 OK");
            exit();
        } else {
            echo 'Acceso no autorizado';
            http_response_code(401);
            exit();
        }
    } else {
        echo 'Usuario o clave inválidos';
        http_response_code(401);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $jwt = $_SERVER['HTTP_AUTHORIZATION'];
    var_dump($_SERVER);
    if (substr($jwt, 0, 6) === "Bearer") {
        $jwt = str_replace("Bearer", "", $jwt);
        echo $jwt;
        try {
            
            $data = JWT::decode($jwt, $key, array('HS256'));
            echo json_encode($data);
            http_response_code(200);
            exit();
        } catch (\Throwable $th) {
            echo 'credenciales erroneas';
            http_response_code(401);
            exit();
            return '';
        }
    }
}
