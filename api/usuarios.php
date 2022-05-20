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
    if (isset($_PUT['nombre'])) {
        $nombre = Limpieza($_PUT['nombre']);
        if (validarTexto($nombre)) {
            $ok = actualizarUsuario('nombre', $nombre, usuarioActual());
        } else {
            echo 'Nombre inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_PUT['apellido'])) {
        $apellido = limpieza($_PUT['apellido']);
        if (validarTexto($apellido)) {
            $ok = actualizarUsuario('apellido', $apellido, usuarioActual());
        } else {
            echo 'Apellido inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_PUT['correo'])) {
        $correo = limpieza($_PUT['correo']);
        if (validarCorreo($correo)) {
            $ok = actualizarUsuario('correo', $correo, usuarioActual());
        } else {
            echo 'Correo inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_PUT['direccion'])) {
        $direccion = limpieza($_PUT['direccion']);
        if (validarDireccion($direccion)) {
            $ok = actualizarUsuario('direccion', $direccion, usuarioActual());
        } else {
            echo 'Dirección inválida';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_PUT['num_hijos'])) {
        $num_hijos = limpieza($_PUT['num_hijos']);
        if (is_numeric($num_hijos)) {
            $ok = actualizarUsuario('num_hijos', $num_hijos, usuarioActual());
        } else {
            echo 'Número de hijos inválido';
            http_response_code(401);
            exit();
        }
    }
    if (isset($_PUT['estado_civil'])) {
        $estado_civil = limpieza($_PUT['estado_civil']);
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
    } else {
        echo 'Error al actualizar';
        http_response_code(401);
        exit();
    }
}
