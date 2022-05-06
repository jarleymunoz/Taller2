<?php
require_once '../vendor/autoload.php'; //libreria de composer
require_once '../db/db.php'; //librerias de base datos
require_once '../libs/tools.php'; //libreria de funciones de validaciones

use Firebase\JWT\JWT; //libreria de jwt

$key = 'my_secret_key';
$time = time();

if (usuarioActual() == '') {
    echo 'Acceso no autorizado';
    http_response_code(401);
    exit();
}

//Actualizacion de datos del usuario excepto usuario y clave
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $_PUT=array();
    parse_str(file_get_contents('php://input'),$_PUT);
    $ok = false;
    if (isset($_GET['nombre'])) {
        $nombre = Limpieza($_GET['nombre']);
        if (validarTexto($nombre)) {
            $ok = actualizarUsuario('nombre', $nombre, usuarioActual());
        } else {
            echo 'Nombre inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_GET['apellido'])) {
        $apellido = limpieza($_GET['apellido']);
        if (validarTexto($apellido)) {
            $ok = actualizarUsuario('apellido', $apellido, usuarioActual());
        } else {
            echo 'Apellido inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_GET['correo'])) {
        $correo = limpieza($_GET['correo']);
        if (validarCorreo($correo)) {
            $ok = actualizarUsuario('correo', $correo, usuarioActual());
        } else {
            echo 'Correo inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_GET['direccion'])) {
        $direccion = limpieza($_GET['direccion']);
        if (validarDireccion($direccion)) {
            $ok = actualizarUsuario('direccion', $direccion, usuarioActual());
        } else {
            echo 'Dirección inválida';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_GET['num_hijos'])) {
        $num_hijos = limpieza($_GET['num_hijos']);
        if (is_numeric($num_hijos)) {
            $ok = actualizarUsuario('num_hijos', $num_hijos, usuarioActual());
        } else {
            echo 'Número de hijos inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_GET['estado_civil'])) {
        $estado_civil = limpieza($_GET['estado_civil']);
        $estados = ['Soltero', 'Casado', 'Otro'];
        if (in_array($estado_civil, $estados)) {
            $ok = actualizarUsuario('estado_civil', $estado_civil, usuarioActual());
        } else {
            echo 'Estado civil inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_PUT['foto'])) {
        $imagen = Limpieza($_PUT['foto']);
        $ruta = '../img/imagen_base64.jpg';
        $imagen = base64_to_jpeg($imagen, $ruta);
        $ok = actualizarUsuario('foto', $imagen, usuarioActual());
    }
    if ($ok) {
        echo 'Datos de usuario actualizado: ' . usuarioActual();
        header("HTTP/1.1 200 OK");
        exit();
    }
}
